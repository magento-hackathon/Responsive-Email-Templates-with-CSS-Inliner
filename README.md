### Responsive Email Templates with CSS Inliner

#### About

This module automatically converts an external set of stylesheets to inline CSS for transactional email templates, to make it easier to write and maintain them.  It also comes with some responsive templates that you can use and customize.

#### Installation

Install using modman.

`modman clone responsive-email git@github.com:magento-hackathon/Responsive-Email-Templates-with-CSS-Inliner.git`

Customize your New Account email in *System* > *Transactional Emails*, using the HTML in `template/email_responsive/ink/account_new.html`

*Note: An alternate template is available at `template/email_responsive/basic/account_new.html` - if you use it, change Mail Style Settings to use basic.css for inline and basic-media-query.css for non-inline.*

Then test it out using preview or by creating an account to generate a welcome email to yourself.

#### Use SASS (Optional)

If you'd like to use SASS, just drop a file in `skin/frontend/base/default/sass/responsive_email`, 
and from `skin/frontend/base/default/` run `compass watch`.  The css will be generated into
`skin/frontend/base/default/css/responsive_email`.  See `basic.scss` as an example.

#### Customization

You can add your own stylesheet to add some customization on top of our base CSS by going into *System* > *Configuration* > *System* > *Mail Style Settings*.
