## Concepts

### Entities

- Causer: Responsible for causing events. Most cases it will be the client's IP, or maybe the logged-in user.
- Subject: The content that should be protected by the paywall.
- Event: Is a access event generated when a _causer_ accesses one _subject_.

### Support

- Authorizers: A condition that may override the access _rule_;
- Rules: The rule responsible for allowing or denying the access of a _causer_ to one _subject_.
