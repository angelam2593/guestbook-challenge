# guestbook-challenge
Guestbook in unstyled HTML where users can register, log in, write/edit/delete messages and leave comments on them.

Regarding the database we have:
- users -> one user has many messages and many comments
- message -> one message belongs to one user and has many comments
- comment -> one comment belongs to one user and belongs to one message

Messages can be created, edited or deleted (by the user that created them).
Any user can insert a comment to any message.
