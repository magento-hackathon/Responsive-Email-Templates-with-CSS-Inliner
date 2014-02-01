### Responsive Email Templates with CSS Inliner

#### About

This module automatically converts an external set of stylesheets to inline CSS for transactional email templates, to make it easier to write and maintain them.  It also comes with some responsive templates that you can use and customize.

#### Installation

Install using modman.

`modman clone responsive-email git@github.com:magento-hackathon/Responsive-Email-Templates-with-CSS-Inliner.git`

Customize your New Account email in *System* > *Transactional Emails*, using the HTML in `template/email_responsive/ink/account_new.html`

*Note: An alternate template is available at `template/email_responsive/basic/account_new.html` - if you use it, change Mail Style Settings to use basic.css for inline and basic-media-query.css for non-inline.*

Then test it out using preview or by creating an account to generate a welcome email to yourself.

#### Customization

You can add your own stylesheet to add some customization on top of our base CSS by going into *System* > *Configuration* > *System* > *Mail Style Settings*.
