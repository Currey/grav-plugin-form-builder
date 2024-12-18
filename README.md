# Form Builder Plugin

The **Form Builder** Plugin is an extension for [Grav CMS](https://github.com/getgrav/grav). It offers a simple Admin interface to build simple contact forms as flex objects to be inserted into page or modular templates.

> This plugin is in very early development and has been released for public testing. There are likely many errors or mistakes. If you see areas of improvement, please do let me know via GitHub issue or on the Grav CMS Discord server.

The intention for this plugin was to create a simple interface for frontend form creation within the Grav Admin interface. Manually creating forms in frontend can be daunting for some end users.

## Installation

Installing the Form Builder plugin can be done in one of three ways: The GPM (Grav Package Manager) installation method lets you quickly install the plugin with a simple terminal command, the manual method lets you do so via a zip file, and the admin method lets you do so via the Admin Plugin.

### GPM Installation (Preferred)

To install the plugin via the [GPM](https://learn.getgrav.org/cli-console/grav-cli-gpm), through your system's terminal (also called the command line), navigate to the root of your Grav-installation, and enter:

    bin/gpm install form-builder

This will install the Form Builder plugin into your `/user/plugins`-directory within Grav. Its files can be found under `/your/site/grav/user/plugins/form-builder`.

### Manual Installation

To install the plugin manually, download the zip-version of this repository and unzip it under `/your/site/grav/user/plugins`. Then rename the folder to `form-builder`. You can find these files on [GitHub](https://github.com/currey/grav-plugin-form-builder) or via [GetGrav.org](https://getgrav.org/downloads/plugins).

You should now have all the plugin files under

    /your/site/grav/user/plugins/form-builder

> NOTE: This plugin is a modular component for Grav which may require other plugins to operate, please see its [blueprints.yaml-file on GitHub](https://github.com/currey/grav-plugin-form-builder/blob/main/blueprints.yaml).

### Admin Plugin

If you use the Admin Plugin, you can install the plugin directly by browsing the `Plugins`-menu and clicking on the `Add` button.

## Configuration

Before configuring this plugin, you should copy the `user/plugins/form-builder/form-builder.yaml` to `user/config/plugins/form-builder.yaml` and only edit that copy.

Here is the default configuration and an explanation of available options:

```yaml
enabled: true
process_fileprefix: '{{ form.name }}-'
process_dateformat: Ymd-His-u
process_extension: txt
process_body: '{% include ''forms/data.txt.twig'' %}'
email_from: '{{ config.plugins.email.from }}'
email_to: '{{ config.plugins.email.to }}'
email_cc: '{{ config.plugins.email.cc }}'
email_bcc: '{{ config.plugins.email.bcc }}'
email_reply_to: '{{ config.plugins.email.reply-to }}'
email_subject: 'Form submission by {{ form.value.name|e }}'
email_body: '{% include ''forms/data.html.twig'' %}'
process_message: 'Thank you for getting in touch!'
```

These configuration options specify defaults to use for form processing and form data handling using Form plugin and Email plugin.

Note that if you use the Admin Plugin, a file with your configuration named form-builder.yaml will be saved in the `user/config/plugins/`-folder once the configuration is saved in the Admin.

## Usage

Create forms in the `Forms` collection menu. Use the `form-builder_from.md` page or module template to select a single form from a list of forms created in Form Builder.

### For template builders

The Form Builder Form Selector can be imported to your template blueprints:

```yaml
import@:
  type: form-builder/form-selector
  context: plugins://form-builder/blueprints/pages
```

See the [Grav Documentation](https://learn.getgrav.org/17/forms/blueprints/advanced-features#embedding-form-importat) for how to use `import@`.

Include the form renderer in your template file:

```twig
{% include "partials/form-renderer.html.twig" with {form_key: page.header.form_builder.form} only %}
```

Additional form parameters can be passed to the form renderer using `form_options`. Example:

```twig
{% include "partials/form-renderer.html.twig" with {form_key: page.header.form_builder.form, form_options: {layout: 'asembl', form_button_outer_classes: 'button-group align-center m-t-2 m-b-1' }} only %}
```

Or:

```twig
{% set vars = {
  form_key: page.header.form_builder.form,
  form_options: {
    layout: 'my-theme-form-layout',
    form_button_outer_classes: 'button-group align-center m-t-2 m-b-1',
  },
} %}

{% include "partials/form-renderer.html.twig" with vars %}
```

## Credits

[Sebastian Laube](https://github.com/bitstarr)'s News plugin helped immensely and inspired me to further develop this flex-object that was initially built into a theme to be a standalone plugin. Snippets of code have been repurposed to fit Form Builder's needs.

Code was also repurposed from [Grav's Admin plugin](https://github.com/getgrav/grav-plugin-admin) (specifically the page `add.js`) to fill filed values based on the field label content.

## To Do

- [ ] Clean up code. It's a mess.
- [ ] Test actual email submissions from rendered forms
- [x] Basic template to render form
- [ ] Flex templates
  - They exist, but they are currently not used and have not been updated.
- [ ] Flex collection template to list forms
- [ ] Further support for addition Form actions and options
- [ ] Separate out Email processing to allow forms to be used for actions other than emailing data.
- [ ] Additional validation options (which will allow for many of the below fields to be supported)
- [ ] Additional field type support:
  - [ ] Checkbox
  - [x] Checkboxes (use `options` array field to set options)
  - [ ] Date
  - [x] Email
  - [ ] File field?
  - [ ] Hidden
  - [ ] Honeypot
  - [ ] Number
  - [ ] Password?
  - [x] Radio (use `options` array field to set options)
  - [ ] Range
  - [ ] Section (Can't have child fields)
  - [x] Select (use `options` array field to set options)
  - [ ] Select Optgroup
  - [ ] Spacer
  - [x] Tel
  - [x] Text
  - [x] Textarea
  - [ ] Toggle
  - [x] URL
  - [ ] Any of the remaining undocumented fields?
