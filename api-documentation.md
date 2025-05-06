# API Documentation


| Method | Endpoint | Description |
|--------|----------|-------------|
## Authentication
| `POST` | `/api/auth/register` | Register a new user | (username, email, password)
| `POST` | `/api/auth/login` | Login to get authentication token |
| `POST` | `/api/auth/logout` | Logout and invalidate token |
| `GET`  | `/api/auth/user` | Get current authenticated user details |
| `POST` | `/api/auth/password/email` | Send password reset email |
| `POST` | `/api/auth/password/reset` | Reset password with token |
| `POST` | `/api/auth/email/verify/{id}/{hash}` | Verify email address |
| `POST` | `/api/auth/email/resend` | Resend email verification link |

## Users
| `GET` | `/api/users` | Get all users (admin only) |
| `GET` | `/api/users/{id}` | Get specific user by ID |
| `PUT` | `/api/users/{id}` | Update user information |
| `DELETE` | `/api/users/{id}` | Delete a user (admin only) |
| `GET` | `/api/users/{id}/projects` | Get all projects for a user |
| `GET` | `/api/users/{id}/issues` | Get all issues assigned to a user |

## Projects
| `GET` | `/api/projects` | Get all accessible projects |
| `POST` | `/api/projects` | Create a new project |
| `GET` | `/api/projects/{id}` | Get a specific project by ID |
| `PUT` | `/api/projects/{id}` | Update a project |
| `DELETE` | `/api/projects/{id}` | Delete a project |
| `GET` | `/api/projects/{id}/issues` | Get all issues for a project |
| `GET` | `/api/projects/{id}/sprints` | Get all sprints for a project |
| `GET` | `/api/projects/{id}/members` | Get all team members of a project |
| `POST` | `/api/projects/{id}/members` | Add a member to a project |
| `DELETE` | `/api/projects/{id}/members/{userId}` | Remove a member from a project |

## Sprints
| `GET` | `/api/sprints` | Get all sprints |
| `POST` | `/api/sprints` | Create a new sprint |
| `GET` | `/api/sprints/{id}` | Get a specific sprint by ID |
| `PUT` | `/api/sprints/{id}` | Update a sprint |
| `DELETE` | `/api/sprints/{id}` | Delete a sprint |
| `GET` | `/api/sprints/{id}/issues` | Get all issues for a sprint |
| `POST` | `/api/sprints/{id}/issues/{issueId}` | Add an issue to a sprint |
| `DELETE` | `/api/sprints/{id}/issues/{issueId}` | Remove an issue from a sprint |

## Statuses
| `GET` | `/api/statuses` | Get all statuses |
| `POST` | `/api/statuses` | Create a new status (admin only) |
| `GET` | `/api/statuses/{id}` | Get a specific status by ID |
| `PUT` | `/api/statuses/{id}` | Update a status (admin only) |
| `DELETE` | `/api/statuses/{id}` | Delete a status (admin only) |

## Issues
| `GET` | `/api/issues` | Get all accessible issues |
| `POST` | `/api/issues` | Create a new issue |
| `GET` | `/api/issues/{id}` | Get a specific issue by ID |
| `PUT` | `/api/issues/{id}` | Update an issue |
| `DELETE` | `/api/issues/{id}` | Delete an issue |
| `POST` | `/api/issues/{id}/assign/{userId}` | Assign an issue to a user |
| `POST` | `/api/issues/{id}/status/{statusId}` | Update issue status |
| `GET` | `/api/issues/{id}/comments` | Get all comments for an issue |

## Comments
| `GET` | `/api/comments` | Get all comments (admin only) |
| `POST` | `/api/comments` | Create a new comment |
| `GET` | `/api/comments/{id}` | Get a specific comment by ID |
| `PUT` | `/api/comments/{id}` | Update a comment (only by author or admin) |
| `DELETE` | `/api/comments/{id}` | Delete a comment (only by author or admin) |

## Search
| `GET` | `/api/search/issues` | Search for issues with query parameters |
| `GET` | `/api/search/projects` | Search for projects with query parameters |
| `GET` | `/api/search/users` | Search for users with query parameters |

## Dashboard
| `GET` | `/api/dashboard/summary` | Get summary of projects and issues for current user |
| `GET` | `/api/dashboard/recent-activity` | Get recent activity for current user |
| `GET` | `/api/dashboard/upcoming-deadlines` | Get upcoming issue deadlines |
