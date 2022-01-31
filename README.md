Flag Statistics for Drupal 7
============================

Contents:

* Introduction
* Maintainers
* Installation
* Configuration
* Support

Introduction
------------

The flag statistics help to get proper count for boolean toggle field which is
attach it to a node, comment, user, or any entity type. Once flags are added
and statistics configs is enabled it will automatically captures all the counts
based on flagged or unflagged event.

Also it supports to views which helps to create a view page. And displays the
statistics of each entity per user.

Maintainers
-----------

Current Flag Maintainers:

* Dhanesh Dhuri (ddhuri)

Installation
------------

Flag 7.x-1.0 is installed like any other Drupal 7 module and requires brief
configuration prior to use.

1. Download the module to your DRUPAL_ROOT/sites/all/modules directory, or where ever you
install contrib modules on your site.
2. Go to Admin > Extend and enable the module.

Configuration
-------------

Configuration of Flag statistics module involves creating flags.

1. Go to Admin > Structure > Flags, and click "Add flag".
2. Select the target entity type, and click "Continue".
3. Enter the flag link text, link type, and any other options.
4. In "FLAG STATISTICS" section enable the flag statistics option which log the
stats for entity after flagging.
5. Enable UnFlag Statistics option which log the stats for entity after
unflagging.
6. Enable Remove Flag Statistics entry for Unflagged entity which delete the
flagged stats after unflagging the entity.
7. Click "Save Flag".

Once you are finished creating flags, you may choose to use Views.

Configuration for view

1. Create a view for "Flag statistics"
2. Add Flag Statistics: Fid field
3. Add relationship content nid for getting specific content based total count.
4. Add contextual filter `id` for getting flag count for each entity.
5. Enable aggregation and make field as countable.

Support
-------

If you experience a problem with flag or have a problem, file a request or
issue on the flag queue at <http://drupal.org/project/issues/flag_stats>.

DO NOT POST IN THE FORUMS.

Posting in the issue queues is a direct line of communication with the module
authors.

No guarantee is provided with this software, no matter how critical your
information, module authors are not responsible for damage caused by this
software or obligated in any way to correct problems you may experience.
