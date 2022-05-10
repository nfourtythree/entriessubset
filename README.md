![Plugin Icon](resources/img/icon.png)

# Entries Subset plugin for Craft CMS 4.x

Craft field type plugin that extends the core Entries field type to give extra settings with ability to restrict by entry type, author or author's user group.

**NOTE**: Craft CMS v4 introduced the condition builder which now allows for most of these features when creating a native Entries field.

![Settings Screenshot](resources/img/settings.png)

## Requirements

This plugin requires Craft CMS 4.0.0 or later.

## Installation

To install the plugin, follow these instructions.

1. Open your terminal and go to your Craft project:

        cd /path/to/project

2. Then tell Composer to load the plugin:

        composer require nfourtythree/entriessubset

3. In the Control Panel, go to Settings → Plugins and click the “Install” button for Entries Subset.

## Entries Subset Overview

This field type is an extension of the built in `Entries` field type. It allows you to specify extra criteria (`EntryType`, `User`, `UserGroup`) in which to restrict the available entries for selection.

#### Examples
You might have a News section which has a few entry types. One of the entry types is "Press Release", you may then have a block where you would like the user to be able to select a "Featured" press release. With the standard `Entries` field type they would be able to select anything from the News section, with this plugin you are able to only allow the selection of those with the "Press Release" entry type.

You might want to limit selection of entries only authored by users in the "Development" or "Copywriters" user groups, this is now possible.

## Using Entries Subset

When creating a new field the extra options for `EntryType` will appear when selecting `Entries Subset` fieldtype

## Entries Subset Roadmap

Some things to do, and ideas for potential features:

* Sunset plugin when Craft CMS has all the features natively

Brought to you by [Nathaniel Hammond (nfourtythree)](https://n43.me)
