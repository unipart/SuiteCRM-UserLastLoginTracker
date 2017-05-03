# SuiteCRM-UserLastLoginTracker
track last login date/time of users

1. login as admin
2. go to admin
3. open studio
4. edit users module/fields
5. add a new DateTime file called 'last_login_date'
6. copy/paste file UserLastLoginTracker.php into folder custom/modules/Users
7. append the following line to file custom/modules/Users/logic_hooks.php

```php
  $hook_array['after_login'][] = Array(1, 'User last login tracker', 'custom/modules/Users/UserLastLoginTracker.php', 'UserLastLoginTracker', 'updateLastLogin');
```
