# v0.7.0
##  2024-12-18

1. [](#improved)
    * Revised javascript to handle field updating to closer match that used by the Admin plugin.
    * Update plugin configuration to have default field data.

# v0.6.0
##  2024-12-16

1. [](#improved)
    * Flex object form initially had a `type: key` field to name fields in the form. This caused issues during form creation as the interface occasionally bugged and would either limit interaction, incorrectly save data, or control the components of a separate field. I now realise that contact forms don't need named keys. Fields now have a `name` attribute to compensate and support form functionality.
    * This also changes the headache that was attempting to programatically set the field key based on a field.

# v0.5.0
##  2024-12-12

1. [](#improved)
    * Rework templates.
    * `partials/form-renderer.html.twig` now handles form rendering and allows for easier inclusion for template builders.
    * Further improvements to flex object form.

# v0.4.5
##  2024-12-11

1. [](#new)
    * Create javascript to handle field value updating based on field label.

# v0.4.0
##  2024-12-10

1. [](#new)
    * Page and modular blueprints and templates to render forms. Barebones. It's intended for template builders to build and customise their own layouts.

# v0.3.0
##  2024-12-04

1. [](#new)
    * Added new field support
    * Flex Object filenames are now derived by their titles.
    * Basic file duplicate protection

# v0.2.0
##  2024-12-03

1. [](#new)
    * Add language support for Form Builder. `en.yaml` created.

2. [](#bugfix)
    * Troubleshoot flex object not saving files due to malformed blueprint form.

# v0.1.0
##  2024-12-02

1. [](#new)
    * Flex object for Form Builder created.
