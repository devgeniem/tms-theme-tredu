# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/en/1.0.0/)
and this project adheres to [Semantic Versioning](http://semver.org/spec/v2.0.0.html).

## [Unreleased]

- TMS-936: Image carousel accessibility fixes
- TMS-900: Order projects by title
- TMS-933: Modaal accessibility fixes
- TMS-926: Add components & blocks to projects-page

## [1.8.4] - 2023-05-24

## Fixed

- TMS-931: Change back-to-top button to focus main-content
- TMS-931: Add screen-reader info for links that go to external sites
- TMS-931: Remove entry__content containers display: grid and add a max-width & centering margins to prevent overflow with zoomed in browsers
- TMS-931: Navigation accessibility fixes
- TMS-931: Program search accessibility fixes for zoomed in browsers
- TMS-931: Added description-list element to single programs info-component
- TMS-932: Moved program search filters into an accordion
- TMS-932: Removed autoatic form submit when selecting program filters
- TMS-932: Fixed program-page elements labels and screen-reader texts & element orders

## [1.8.3] - 2023-05-02

## Fixed

- PIEN-8394: Add API image to og:image tag #154

## [1.8.2] - 2023-04-28

### Fixed

- PIEN-8585: Check empty links #153

## [1.8.1] - 2023-04-27

### Fixed

- PIEN-8524: Display API image #152

## [1.8.0] - 2023-04-11

- PIEN-8157: PHP 8.1

### Added

- PIEN-8439: Add pageID meta tag #151

## [1.7.5] - 2023-03-09

### Added

- TMS-923: Load more -functionality added to program-lift component
- TMS-889: "Go back"-link added to single Dial Tredu pages
- TMS-902: Add cron job to draft TreduEvent posts based on custom end_date field

## [1.7.4] - 2023-01-09

### Fixed

- PIEN-8047: Change build process back to Webpack instead of Laravel Mix.

## [1.7.3] - 2022-12-29

### Fixed

- PIEN-8045: Change subpage-block to work with all post types that use it.

## [1.7.2] - 2022-02-11

### Fixed

- TMS-916: Fix order of selected api contacts. #130

## [1.7.1] - 2022-10-17

### Fixed

- PIEN-7726: Fix showing images in home page highlight.

## [1.7.0] - 2022-10-03

### Added

- PIEN-7658: Settings field for body scripts.

### Fixed

- PIEN-7666: Hide non-public pages from breadcrumbs.

## [1.6.0] - 2022-09-22

### Added

- TMS-903: Add dial tredu cpt caps to author role #122
- TMS-897: Add video component & block video align option

### Fixed

- PIEN-7639: Fix events sharing link.

## [1.5.0] - 2022-09-20

### Fixed

- PIEN-7610: Handle api_image_url and fix case where featured image doesn't exist.

### Added

- TMS-910: Add play.quickchannel.com embed support

## [2022-09-07] - 1.4.0

### Added

- TMS-903: Add dial tredu cpt caps to author role #122

### Fixed

- PIEN-7508: Fix drupal contacts on contact page template. #121
- TMS-822: Fix layout call to action image meta contrast #93
- TMS-883: Allow PlaceOfBusiness block in Program CPT

## [2022-08-23] - 1.3.0

- TMS-894: Fix composer
- TMS-891: Add chat #119
- TMS-893: Fix Tredu events order #117
- TMS-882: Fix field name conflict #112
- TMS-884: Dial Tredu CPT & Single view #110
- TMS-855: Redipress search includes #113
- TMS-855-2: Allow cpts in search #114
- TMS-885: Dial Tredu page template #111

## [2022-06-21] - 1.2.0

### Added

- TMS-872: Add icons to to breadcrumbs #104
- TMS-807: Add Favicon to settings #103
- TMS-785: Announce success message for screen readers #108

### Changed

- TMS-825: Hide result info #106
- TMS-881: Improve drupal news support #109
- TMS-880: Display drupal image on single-post view #107

## [2022-06-16] - 1.1.0

### Added

- TMS-877: Handle imports with tms-plugin-contact-importer #100
- TMS-821: Events #97
- TMS-873: Show drupal image on home result card #95
- TMS-131: Add Readspeaker #99
- TMS-705: Setup user roles & permissions #98
- TMS-745: Project page template #80
- TMS-682: Blog archive #94
- TMS-707: Tredu Events #71
- TMS-744: Single Project #76
- TMS-859: Convert PageProgram textarea to wysiwyg #92

## [2022-06-01] - 1.0.0

### Changed

- TMS-847: Prevent double slashes on api url #90
- TMS-843: Fix contact block & layout alignment, restore Gutenberg text align classes #89
- TMS-850: Reduce padding on quote with white background #91

## [2022-05-30] - 0.12.0

### Added

- TMS-671: Add PlaceOfBusiness block #66

### Changed

- TMS-836: Tampere API Performance optimizations #86
- TMS-838: Disable navigation top level parent links #87

## [2022-05-23] - 0.11.0

### Added

- TMS-833: Double the program price character limit & hyphenate program info
- TMS-832: Add option to hide single program Apply info #83
- TMS-666: Single blog #60
- TMS-736: Single program type term view #62

### Changed

- TMS-823: Remove EventFields sort field #81

## [2022-05-16] - 0.10.0

### Changed

- TMS-820: Add AccordionContacts layout #68
- TMS-664: Remove Grid & Acc icons blocks from Post #79

## [2022-05-12] - 0.9.0

### Changed

- TMS-810: Separate url for English requests #78
- TMS-817: Display API contacts #70
- TMS-734: Single program

## [2022-05-05] - 0.8.0

### Changed

- TMS-816: Unify image meta #67

### Added

- TMS-703: Add Sitemap layout.
- TMS-709: Program search box

## [2022-05-02] - 0.7.0

### Added

- TMS-820: Add AccordionContacts layout
- TMS-737: Project CPT and Portfolio taxonomy #63

## [2022-04-26] - 0.6.0

### Added

- TMS-852: Program layout #96
- TMS-698: Add form
- TMS-688: Table block
- TMS-708: Program Call to Action

### Fixed

- TMS-805: Fix no results text logic
- TMS-734-hotfix-programs-layouts-and-blocks

## [2022-04-25] - 0.5.0

### Added

- TMS-680: Notice banner
- TMS-743: Hero layout for front page
- TMS-697: Contacts
- TMS-804: Add cap copy_posts to editors & authors #53
- TMS-681: Add articles layout. #50
- TMS-678: Image carousel #47
- TMS-695: Related posts. #42
- TMS-669: Materials page template #43
- TMS-702: Accessibility icons #41
- TMS-735: Program Search #38
- TMS-801: Add ApplyType tax #52

## [2022-04-21] - 0.4.1

- TMS-743: Hero layout for front page #49
- TMS-697: Contacts #36

### Fixed

- TMS-223-API: Load choices with ajax #51

## [2022-04-12] - 0.4.0

### Added

- TMS-684: Video embed #39
- TMS-769: Countdown block #37
- TMS-735: Program Search #38
- TMS-697: Share links #35
- TMS-766: Fix displaying image author on Call to Action.
- TMS-691: Enable social media block #30
- TMS-692-2: Change accent key figure color #29
- TMS-685: Materials #33
- TMS-674: Image #28
- TMS-675: Add Logo Wall #27
- TMS-694: Image gallery #26

## [2022-04-07] - 0.3.0

- TMS-693: Grid #24
- TMS-665: Color fix. #25
- TMS-690: Add subpages #22
- TMS-689: Add link list #21
- TMS-687: Quote block #20
- TMS-676: Icon links #12

## [2022-04-04] - 0.2.0

### Added

- TMS-686: Accordion block
- TMS-699: Image banner #10
- TMS-700: Header notice #8
- TMS-679: Enable map layout & block #11
- TMS-665: Add article archive.
- TMS-760: Add default filter image filter for material images #7
- TMS-672: 404 Page styles
- TMS-660: Header, Footer, Navgation
- TMS-661: Siblings navigation for page
- TMS-662: Breadcrumb in header
- TMS-665: Add article archive.
- TMS-672: 404 Page styles
- TMS-673: Content page hero
- TMS-686: Accordion block

## [2021-03-16] - 0.1.0

### Added

- TMS-734: Progam ACF #1
