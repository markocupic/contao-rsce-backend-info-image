<p align="center"><a href="https://github.com/markocupic"><img src="docs/logo.png" width="200"></a></p>

# Contao Rocksolid Custom Element Backend Info Image for Contao CMS

This Plugin extends [madeyourday/contao-rocksolid-custom-elements](https://github.com/madeyourday/contao-rocksolid-custom-elements).
The extension allows you to display a thumbnail beside the rsce element type.

![Contao Rocksolid Custom Element Backend Info Image](docs/backend.png)

## Image configuration
The image configuration can be made in the config file of your rocksolid custom element.
![Contao Rocksolid Custom Element Backend Info Image](docs/config.png)

## Installation

Execute the following command to install the extension:
```bash
composer require markocupic/contao-rsce-backend-info-image
```

## Plugin configuration

The image html markup and the "add_after_regex_pattern" can be customized in your project `config/config.yml`.

```yaml
# config/config.yml
markocupic_contao_rsce_backend_info_image:
 image_markup: '<div class="long widget rsce-backend-info-image"><div class="rsce-backend-info-image-inner"><img src="###IMAGE_SRC###" alt="###IMAGE_ALT###"></div></div>'
 add_after_regex_pattern: '/<legend onclick="AjaxRequest\.toggleFieldset\(this,\'([a-z]+)_legend\',\'([a-zA-Z0-9-_]+)\'\)">([a-zA-Z0-9 ]+)<\/legend>/'
```

---
This extension has been sponsored by [kda.studio](https://www.kda.studio/), 63067 Offenbach am Main, Germany
<p align="left"><a href="https://www.kda.studio" title="kda.studio"><img src="docs/kda.png" width="180"></a></p>
