# Laravel Paywall

## Contracts

There are three main concepts: Events, Authorizer and Rule. Probably the best way of understading them is by checking the already implemented ones.

### Events

Represents a association between an `User` and a `Subject`.

### Authorizer

Must implement the `allows` method. This method must return a boolean whether the user can or cannot access a determined subject, overriding the rules.

### Rules

Must implement the `allows` method. Normally it will verify the events between `User` and `Subject`.

## Default usage

```php
@paywall
  This content is blocked after reading 3 different contents.
@else
  If the content is blocked, this is shown.
@endpaywall
