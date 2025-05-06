<?php

namespace App\Http\Controllers;

use App\Http\Requests\Api\Auth\LoginRequest;
use App\Http\Requests\Api\Auth\RegisterRequest;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Api\Auth\PasswordResetRequest;
use App\Http\Requests\Api\Auth\PasswordEmailRequest;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cookie;

class AuthController extends Controller
{
    protected $userController;

    public function __construct(UserController $userController)
    {
        $this->userController = $userController;
    }

    public function register(RegisterRequest $request)
    {
        $user = $this->userController->store($request);

        // Generate verification token
        $user->email_verification_token = sha1($user->email);
        $user->save();

        // Send verification email
        $this->sendVerificationEmail($user);

        $accessToken = JWTAuth::fromUser($user, ['exp' => now()->addDays(1)->timestamp]);
        $refreshToken = JWTAuth::fromUser($user, ['exp' => now()->addDays(30)->timestamp, 'type' => 'refresh']);

        $user->save();

        return response()->json([
            'suceess' => true,
            'user' => $user,
            // 'access_token' => $accessToken,
            // 'refresh_token' => $refreshToken,
            'message' => 'Registration successful. Please check your email to verify your account.',
        ], 200)

        ->withCookie(
            Cookie::make(
                'access_token',
                $accessToken,
                60,
                '/',
                'localhost',
                true,
                false,
                false,
                'None'
            )
        )
        ->withCookie(
            Cookie::make(
                'refresh_token',
                $refreshToken,
                60 * 24 * 30,
                '/',
                'localhost',
                true,
                false,
                false,
                'None'
            )
        );
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');

        // this function try to create the token when user login and if the credentail not valide pop up 401 server error than pop up 500
        try {
            if (!$accessToken = JWTAuth::attempt($credentials, ['exp' => now()->addDays(1)->timestamp])) {
                return response()->json(['message' => 'Invalide credentials'], 401);
            }
        } catch (JWTException $e) {
            return response()->json(['message' => 'Could not create token'], 500);
        }

        // check who it login 
        $user = JWTAuth::user();

        $refreshToken = JWTAuth::fromUser($user, ['exp' => now()->addDays(30)->timestamp, 'type' => 'refresh']);
        $user->save();

        return response()->json([
            'user' => [
                'id' => $user->id,
                'username' => $user->username,
                'email' => $user->email,
                'verified' => true
            ],
            // 'access_token' => $accessToken,
            // 'refresh_token' => $refreshToken,
            'token_type' => 'Bearer',
        ])


            ->withCookie(
                Cookie::make(
                    'access_token',
                    $accessToken,
                    60,
                    '/',
                    'localhost',
                    true,
                    false,
                    false,
                    'None'
                )
            )
            ->withCookie(
                Cookie::make(
                    'refresh_token',
                    $refreshToken,
                    60 * 24 * 30,
                    '/',
                    'localhost',
                    true,
                    false,
                    false,
                    'None'
                )
            );
    }

    public function refresh(Request $request)
    {
        try {
            $refreshToken = $request->cookie('refresh_token');
    
            if (!$refreshToken) {
                return response()->json(['message' => 'Refresh token not found'], 401);
            }
    
            $user = JWTAuth::setToken($refreshToken)->toUser();
    
            if (!$user) {
                return response()->json(['message' => 'User not found'], 404);
            }
    
            $newAccessToken = JWTAuth::fromUser($user, ['exp' => now()->addDay()->timestamp]);
            $newRefreshToken = JWTAuth::fromUser($user, ['exp' => now()->addDays(30)->timestamp, 'type' => 'refresh']);
    
            return response()->json([
                'success' => true,
                'message' => 'Tokens refreshed successfully',
                'token_type' => 'Bearer',
            ])
            ->withCookie(
                Cookie::make(
                    'access_token',
                    $newAccessToken,
                    60,
                    '/',
                    'localhost',
                    true,
                    false,
                    false,
                    'None'
                )
            )
            ->withCookie(
                Cookie::make(
                    'refresh_token',
                    $newRefreshToken,
                    60 * 24 * 30,
                    '/',
                    'localhost',
                    true,
                    false,
                    false,
                    'None'
                )
            );
    
        } catch (JWTException $e) {
            return response()->json(['message' => 'Invalid or expired refresh token'], 401);
        }
    }

    public function logout(Request $request)
    {
        try {
            $token = $request->bearerToken();
            if (!$token) {
                return response()->json(['message' => 'No token provided'], 401);
            }

            JWTAuth::setToken($token)->invalidate();

            return response()->json(['message' => 'Logged out successfully']);
        } catch (JWTException $e) {
            return response()->json(['message' => 'Failed to logout: ' . $e->getMessage()], 500);
        }
    }

    public function getUserInfo(Request $request)
    {
        $user = Auth::user();
    
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }
    
        // Define accessible endpoints by role
        $permission = [
            'admin' => ['dashboard', 'users', 'projects'], 
            'user' => ['dashboard', 'projects', 'reports', 'tasks'],               
        ];
    
        $userRole = $user->systemRole;
    
        return response()->json([
            'user' => [
                'id' => $user->id,
                'username' => $user->username,
                'email' => $user->email,
                'gender' => $user->gender,
                'systemRole' => $userRole,
                'verified' => !is_null($user->email_verified_at),
                'accessibleEndpoints' => $permission[$userRole] ?? [],
            ]
        ]);
    }
    
    public function resetPassword(PasswordResetRequest $request)
    {
        $token = $request->token;
        $email = $request->email;
        $password = $request->password;

        $user = User::where('email', $email)->first();

        if (!$user) {
            return response()->json(['message' => 'Email not found'], 404);
        }

        if ($user->remember_token !== $token) {
            return response()->json(['message' => 'Invalid reset token'], 401);
        }

        $user->password = Hash::make($password);

        $accessToken = JWTAuth::fromUser($user, ['exp' => now()->addDays(1)->timestamp]);
        $refreshToken = JWTAuth::fromUser($user, ['exp' => now()->addDays(30)->timestamp, 'type' => 'refresh']);


        $user->remember_token = $refreshToken;
        $user->save();

        return response()->json([
            'user' => [
                'id' => $user->id,
                'username' => $user->username,
                'email' => $user->email,
            ],
            // 'access_token' => $accessToken,
            // 'refresh_token' => $refreshToken,
            'message' => 'Password reset successfully'
        ], 200)

        ->withCookie(
            Cookie::make(
                'access_token',
                $accessToken,
                60,
                '/',
                'localhost',
                false,
                false,
                false,
                'None'
            )
        )
        ->withCookie(
            Cookie::make(
                'refresh_token',
                $refreshToken,
                60 * 24 * 30,
                '/',
                'localhost',
                false,
                false,
                false,
                'None'
            )
        );
    }

    public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)->stateless()->redirect()->getTargetUrl();
    }
    
    public function handleProviderCallback(Request $request, $provider)
    {
        try {
            $socialUser = Socialite::driver($provider)->stateless()->user();
            
            // Find existing user or create new one
            $user = User::where('email', $socialUser->getEmail())->first();
            
            if (!$user) {
                // Create new user from social data
                $user = new User([
                    'username' => $socialUser->getName() ?? $socialUser->getNickname() ?? explode('@', $socialUser->getEmail())[0],
                    'email' => $socialUser->getEmail(),
                    'password' => Hash::make(Str::random(16)), // Random secure password
                ]);
                
                // Set provider info
                $user->provider = $provider;
                $user->provider_id = $socialUser->getId();
                $user->profileURL = $socialUser->getAvatar();
                $user->email_verified_at = now(); 
                $user->save();
            }
    
            // Generate tokens consistent with other auth methods
            $accessToken = JWTAuth::fromUser($user, ['exp' => now()->addDays(1)->timestamp]);
            $refreshToken = JWTAuth::fromUser($user, ['exp' => now()->addDays(30)->timestamp, 'type' => 'refresh']);
            
            // Save refresh token if needed
            $user->save();
    
            // Return same response format as other auth methods
            return redirect()->away('http://localhost:3000/profile')
            ->withCookie(
                Cookie::make(
                    'access_token',
                    $accessToken,
                    60,
                    '/',
                    'localhost',
                    true,
                    false,
                    false,
                    'None'
                )
            )
            ->withCookie(
                Cookie::make(
                    'refresh_token',
                    $refreshToken,
                    60 * 24 * 30,
                    '/',
                    'localhost',
                    true,
                    false,
                    false,
                    'None'
                )
            );
        } catch (\Exception $e) {
            Log::error('Social login error', [
                'provider' => $provider,
                'error' => $e->getMessage(),
            ]);
            
            return response()->json(['message' => 'Social login failed: ' . $e->getMessage()], 500);
        }
    }

    public function sendPasswordResetEmail(PasswordEmailRequest $request)
    {
        $email = $request->email;
        $password = $request->password;

        $user = User::where('email', $email)->first();

        // Check if password matches
        if (!$user || !Hash::check($password, $user->password)) {
            return response()->json(['message' => 'Invalid email or password'], 401);
        }

        // Generate token and save
        $token = Str::random(60);
        $user->remember_token = $token;
        $user->save();

        // Generate URL
        $resetUrl = env('FRONTEND_URL', 'http://localhost:3000') . '/reset-email?token=' . $token . '&email=' . urlencode($email);

        try {
            Mail::send('emails.reset_email', [
                'resetUrl' => $resetUrl,
                'user' => $user
            ], function ($message) use ($user) {
                $message->to($user->email);
                $message->subject('Reset Your email');
            });

            return response()->json(['message' => 'Email reset link has been sent to your email']);
        } catch (\Exception $e) {
            Log::error('Failed to send password reset email', [
                'error' => $e->getMessage(),
                'user_id' => $user->id ?? null,
                'email' => $user->email ?? $email
            ]);
            return response()->json(['message' => 'Could not send reset email: ' . $e->getMessage()], 500);
        }
    }
    protected function sendVerificationEmail(User $user)
    {
        $frontendUrl = env('FRONTEND_URL', 'http://localhost:3000');
        $verificationUrl = $frontendUrl . '/verify-email?token=' . $user->email_verification_token . '&email=' . urlencode($user->email);

        try {
            Mail::send('emails.email_verify', [
                'verificationUrl' => $verificationUrl,
                'user' => $user
            ], function ($message) use ($user) {
                $message->to($user->email)
                    ->subject('Verify Your Email Address');
            });

            return true;
        } catch (\Exception $e) {
            Log::error('Failed to send verification email', [
                'error' => $e->getMessage(),
                'user_id' => $user->id,
                'email' => $user->email
            ]);
            return false;
        }
    }

    public function verifyEmail(Request $request, $id, $hash)
    {
        $user = User::findOrFail($id);

        // Check if URL hash matches the expected hash
        if (!hash_equals($hash, sha1($user->email))) {
            return response()->json(['message' => 'Invalid verification link'], 400);
        }

        if (!is_null($user->email_verified_at)) {
            return response()->json(['message' => 'Email already verified'], 200);
        }

        $user->email_verified_at = now();
        $user->email_verification_token = null;
        $user->save();

        return response()->json(['message' => 'Email verified successfully'], 200);
    }

    public function resendVerificationEmail(Request $request)
    {
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        if (!is_null($user->email_verified_at)) {
            return response()->json(['message' => 'Email already verified'], 200);
        }

        // Generate new verification token
        $verificationToken = Str::random(60);
        $user->email_verification_token = $verificationToken;
        $user->save();

        // Send verification email
        $emailSent = $this->sendVerificationEmail($user);

        if ($emailSent) {
            return response()->json(['message' => 'Verification email has been sent'], 200);
        } else {
            return response()->json(['message' => 'Could not send verification email'], 500);
        }
    }
}