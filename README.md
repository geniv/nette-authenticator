Security user authenticator
===========================

Installation
------------

```sh
$ composer require geniv/nette-authenticator
```
or
```json
"geniv/nette-authenticator": ">=1.0.0"
```

require:
```json
"php": ">=5.6.0",
"nette/nette": ">=2.4.0",
"dibi/dibi": ">=3.0.0"
```

Include in application
----------------------

### available source drivers:
- Array (base ident: key, id, hash)
- Neon (same format like Array)
- Dibi (base ident: id, login, hash, active, role, added)
- Combine (combine driver Array, Neon, Dibi; order authenticate define combineOrder)

hash is return from: `Passwords::hash($password)`

neon configure:
```neon
# login
authenticator:
#   autowired: false    # default null, false => disable autowiring (in case multiple linked extension) | self
    source: "Dibi"
    tablePrefix: %tablePrefix%
#   source: "Array"
    userlist: 
        Foo:
            id: 1
            hash: "@@hash!@@"
            role: guest
            username: mr Foo
        Bar:
            id: 2
            hash: "@@hash!@@"
            role: moderator
            username: mr Bar
#   source: "Neon"
#   path: %appDir%/authenticator.neon
#   source: "Combine"
#   combineOrder:
#       - Array
#       - Neon
#       - Dibi
#   classArray: Authenticator\Drivers\ArrayDriver
#   classNeon: Authenticator\Drivers\NeonDriver
#   classDibi: Authenticator\Drivers\DibiDriver
```

neon configure extension:
```neon
extensions:
    authenticator: Authenticator\Bridges\Nette\Extension
```

presenters:
```php
use Authenticator\LoginForm;

protected function createComponentLoginForm(LoginForm $loginForm)
{
    //$loginForm->setTemplatePath(__DIR__ . '/templates/LoginForm.latte');
    // callback from $loginForm (support redirect)
    $loginForm->onLoggedIn[] = function (User $user) {
        $this->flashMessage('Login!', 'info');
        $this->redirect('this');
    };
    $loginForm->onLoggedInException[] = function (AuthenticationException $e) {
        $this->flashMessage('Login exception! ' . $e->getMessage(), 'danger');
    };
    $loginForm->onLoggedOut[] = function (User $user) {
        $this->flashMessage('Logout!', 'info');
        $this->redirect('this');
    };

    //OR

    // callback from $this->user (don't support redirect)
    $this->user->onLoggedIn[] = function (User $user) {
        $this->flashMessage('Login!', 'info');
    };
    $this->user->onLoggedOut[] = function (User $user) {
        $this->flashMessage('Logout!', 'info');
    };
    return $loginForm;
}
```

usage:
```latte
    {if !$user->isLoggedIn()}
        {control loginForm}
    {else}
        <a n:href="loginForm:Out!">Logout</a>
    {/if}
```
