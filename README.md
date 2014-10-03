#Auto-Assign User Group
#plugin for Craft CMS

Automatically assign newly created users to a specific user group.

##The Hypothetical

Imagine this scenario...

You are the super-admin of a website. On that site, you'll need a small group of business-level admins, and an unlimited amount of ordinary members.

 - Super-admin (you)
 - Admins
 - Members

Members cannot register themselves... Their accounts must be created by Admins.

Admins can create new users, **but should not be able to edit the individual permissions of each member**. This is a bit of a problem, since Craft allows you to _edit the individual permissions of a user_ from the exact same page as you would _assign them to a specific user group_.

##The Solution?

This plugin will allow you (the Super-admin) to pre-determine which group a newly registered user will belong to. So in the case of our example, a newly registered user would be automatically assigned to the "Members" group. What's important about this, **you can now prevent Admins from managing user permissions at all**. They no longer need the ability to place a new user into the Members group, so they will no longer have access to _any_ of the custom permissions options.

##Using the Plugin

Once you've installed the plugin, visit:

- **Settings > Plugins > Auto-Assign User Group**

There you will see a list of available user groups, under the heading "User Group(s) to be Auto-Assigned". Simply check every group that a new user should be automatically assigned to, and hit Save. **From that point on, every new user created through the Control Panel will automatically belong to the specified group.**

If no user groups have been created yet, you'll see a message that "No user groups exist" and a link to "Create one now..."

##Important Note

The user group(s) selected will be assigned to a new user when the new user is created **through the Control Panel**. If your website allows users to register through a front-end form, their group will be automatically assigned in a different way.

If you'd like to auto-assign a user group for anyone who registers via the front-end form, that can be specified on the **Settings > Users** page, below the "Allow public registration?" checkbox.
