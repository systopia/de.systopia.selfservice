# Self-Service vs. Traditional Login

We see the following advantages for the selfservice method:

- The less complicated the login is, the more people will do it. So if you want to reach as much of your constituents as possible you should use the easiest way to let them get access to the contact data form.
- The effort to implement a traditional login method with passwords is higher. All contacts will have to become a Drupal user to gain access to Drupal and the form for their contact data. There is a constant amount of work involved in creating new user accounts and blocking unused accounts. This is not necessary for the self-service method. **TODO: Wie würde der Log-In wieder entzogen werden?**
- With the self-service method, it is easy for an organisation to make a handover to a new person in charge. There is no need of a password rotation involved. **TODO: why not?**

## Security considerations

Since we all are used to using th login password method for access, it feels safer at first. But if you look a little bit closer, there is always the email address that is important. There the first login data is sent. There the new password is sent, if you use the *password forgotten* workflow. So if somebody is in control of the mailbox or monitoring the email traffic, they will gain access. Because of this, we see no heightend security issues from the selfservice method.

Additionally, there is the problem of people choosing unsave passwords. You can counter that by forcing them so choose a saver password, but that results in fewer people using the login and also more trouble for them to remember the password.