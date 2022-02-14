<?php
$admin_help_url = admin_url('admin.php?page=theme-help');
?>
<div id="theme-help" class="i-am-a-fancy-admin">
	<div class="container">
		<h1>Pegasus Magazine Site Help - for Content Creators</h1>

		<div class="sections">
			<ul>
				<li class="section"><a href="#intro">Intro</a></li>
				<li class="section"><a href="#issues">Issues</a></li>
				<li class="section"><a href="#stories">Stories</a></li>
				<li class="section"><a href="#photo-essays">Photo Essays</a></li>
				<li class="section"><a href="#shortcodes">Shortcodes</a></li>
			</ul>
		</div>
		<div class="fields">
			<ul>
				<li class="section" id="intro">
					<h2>Intro</h2>
					<p>
						The goal of the help section is to familiarize yourself with the Pegasus website and the different types of content that can be created. This should also help you understand the flow of content creation for Pegasus.
					</p>
					<p>
						Keep in mind that this help section assumes a basic working knowledge of the WordPress admin interface. If you are unfamiliar with WordPress, please <a href="https://wordpress.org/support/article/first-steps-with-wordpress/#start-at-the-top" target="_blank">check out a few tutorials</a> before getting started.
					</p>
					<br/>
					<p>
						The primary types of content that can be created in this site are <strong>Issues</strong>, <strong>Stories</strong>, and <strong>Photo Essays</strong>.
					<p>
						<strong>Issues</strong> are a means of categorizing a group of Stories much like the physical magazine. Issues also exist as a type of post, which serves as the landing page for the website when that Issue is active.
					</p>
					<p>
						<strong>Stories</strong> are where the meat of the content will reside. The Stories that will live on the website are hand picked from the physical magazine.
					</p>
					<p>
						The <strong>Photo Essay</strong> is a type of content used to create a slideshow of images with captions in a Story or Issues. Photo Essays can be embedded onto Stories or Issues using a <strong>Shortcode</strong>.
					</p>
					<br/>
					<p>
						<strong>Shortcodes</strong> are small snippets of code, wrapped in square brackets [], that do some function or add some predefined content to your story content. On this site, we the <a href="https://github.com/UCF/Athena-Shortcodes-Plugin/wiki" target="_blank">Athena Shortcode plugin</a> which has built-in shortcodes that enable adding things like rows, columns, jumbotrons and more.
					</p>
				</li>

				<li class="section" id="issues">
					<h2>Issues</h2>

					<p>
						<strong>Issues</strong> are used to categorize Stories just like in the physical magazine. They exist in two different forms: as a <a target="_blank" href="https://wordpress.org/support/article/taxonomies/">taxonomy</a> (a means of grouping content, like categories or tags) and as a type of post, which serves as the landing page/cover for the website when that issue is active.
					</p>

					<p><strong>Navigation:</strong></p>
					<ul class="section-nav">
						<li>
							<a href="<?php echo $admin_help_url; ?>#issues-creating-issue">Creating Issues</a>
							<ul>
								<li><a href="<?php echo $admin_help_url; ?>#issues-create-taxonomy">1. Create the Taxonomy Term</a></li>
								<li><a href="<?php echo $admin_help_url; ?>#issues-create-post">2. Create the Issue Cover</a></li>
							</ul>
						</li>
						<li>
							<a href="<?php echo $admin_help_url; ?>#issues-modifying">Modifying an Issue</a>
						</li>
						<li>
							<a href="<?php echo $admin_help_url; ?>#issues-active">Updating the Active Issue</a>
						</li>
					</ul>

					<h3 id="issues-creating-issue">Creating Issues</h3>
					<p>
						Creating an issue involves two core steps: creating the taxonomy term for the issue, and creating the issue cover page. The taxonomy term will allow you to assign stories to that issue, while the issue cover page lists those stories.
					</p>

					<h4 id="issues-create-taxonomy">1. Create the Taxonomy Term</h4>
					<p>
						To create a new Issue taxonomy term, hover over the “Stories” link in the left-hand WordPress admin menu, and click "Issues". You'll see a list of existing Issues on the right, grouped by year, and a form on the left for creating new issues.
					</p>
					<p>
						Before creating the new issue, make sure a year grouping has already been defined in the list to the right. (i.e., if you're creating the issue "Summer 2014", make sure the term "2014" exists.) If it doesn't, use the form on the left to add it.
					</p>
					<ul>
						<li>The "Name" value will be the year.</li>
						<li>The "Slug", "Parent" and "Description" fields should be left blank.</li>
					</ul>
					<p>
						Click the "Add New Issue" button to create the new term. You should see the term appear in the list on the right after a second or two.
					</p>
					<p>
						If the year term for your new issue exists, use the form on the left to create it.
					</p>
					<ul>
						<li>The "Name" value will be the season and year, i.e. "Summer 2014".</li>
						<li>The "Slug" value will be created automatically for you and should be left blank.</li>
						<li>The "Parent" value should be the year term, i.e. "2014".</li>
						<li>Leave the "Description" field blank.</li>
					</ul>
					<p>
						Click the "Add New Issue" button to save the new issue term.
					</p>

					<h4 id="issues-create-post">2. Create the Issue Cover</h4>
					<p>
						To create a new Issue cover, hover over the "Issues" link in the left-hand WordPress admin menu, and click "Add New".
					</p>

					<h5>Add a Title</h5>
					<p>
						Enter the title of the issue in the title field at the top of the screen. This title should match the issue taxonomy term <strong>exactly.</strong>
					</p>
					<div class="well">
						<p>
							<strong>Important:</strong> The issue taxonomy term and issue cover names must match <strong>exactly</strong> for the term and cover post to relate to each other correctly. If you change the title (or slug) of an issue at any time, make sure that both the taxonomy term and issue cover title and slug are updated.
						</p>
					</div>

					<h5>Add a Thumbnail</h5>
					<p>
						The Issue's Featured Image should be a screenshot of the print magazine's cover for that issue. The Issue's thumbnail is used on the site's homepage on newer issues and in the site archives. To set the Featured Image, click the “Set featured image” link in the Featured Image metabox on the right side of the screen. Upload or select an image as usual and click the “Set featured image” button.
					</p>
					<div class="well">
						<p><strong>Thumbnail Specs/Guidelines:</strong></p>
						<ul>
							<li>Issue thumbnails should be <strong>190x248px</strong></li>
							<li>Thumbnails should be a <strong>.jpg</strong>. Adding transparency is not recommended.</li>
							<li>
								Running thumbnails through an image compression service like <a href="https://imageoptim.com/mac" target="_blank">ImageOptim</a> is recommended.
							</li>
						</ul>
					</div>

					<h5>Issue Content</h5>
					<p>
						Issue covers that use the Default Issue Template do not use the content (WYSIWYG) editor; all stories and past issues of Pegasus are filled in automatically, so this field should be left blank.
					</p>

					<h5>Issue Customization</h5>
					<p>
						The Issue Fields metabox, located under the issue content editor, contains set of extra fields that add custom settings to the issue cover. Most of these fields are straightforward and provide descriptions.
					</p>
					<p>
						Generally, you will only need to add a Cover Story and set the 3 Featured Stories. The Issue Template value should not be modified.
					</p>
					<div class="well">
						<p><strong>Things to keep in mind:</strong></p>
						<ul>
							<li>Do not modify fields labeled as "DEPRECATED". They are only used by old issues.</li>
						</ul>
					</div>

					<h3 id="issues-modifying">Modifying an Issue</h3>
					<p>
						Because issues are composed of both a taxonomy term and post type, both will need to be modified if the title or slug of the issue changes.
					</p>
					<p>
						Issue taxonomy terms can be modified by hovering over "Stories" in the WordPress admin menu and clicking "Issues". Hover over the issue term and click "Edit" (you can alternatively use the Quick Edit option if you're only modifying the title and/or slug.)
					</p>
					<p>
						Issue covers can be modified by clicking "Issues" in the WordPress admin menu. Click the title of the issue cover to go to the post editor.
					</p>

					<h3 id="issues-active">Updating the Active Issue</h3>
					<div class="well">
						<p>
							<strong>Note: Updating the active issue requires access to the site's Theme Options menu.</strong>
						</p>
					</div>
					<p>
						Before switching out the current active issue, first make sure that all stories for the new issue have been reviewed and are free of spelling/grammatical errors, all media assets are uploaded and working correctly, and that the markup generated by the WYSIWYG editor are free of markup errors. Also make sure that the issue cover loads correctly, and that all stories appear where they should appear.
					</p>
					<p>
						To switch out the active issue, click "Theme Options" in the WordPress admin menu, and click "Issues". Select the new issue in the dropdown menu and click "Save Options".
					</p>
					<p>
						The active issue will be switched immediately upon saving. Make sure everything works correctly after the switch!
					</p>
				</li>

				<li class="section" id="stories">
					<h2>Stories</h2>
					<p>
						<strong>Stories</strong> are a type of post. They are similar to standard WordPress posts, except they are
						customized to display story-specific information.
					</p>

					<p><strong>Navigation:</strong></p>
					<ul class="section-nav">
						<li>
							<a href="<?php echo $admin_help_url; ?>#stories-creating-stories">Creating Stories</a>
							<ul>
								<li><a href="<?php echo $admin_help_url; ?>#stories-add-title">Add a Title</a></li>
								<li><a href="<?php echo $admin_help_url; ?>#stories-issue-tags">Assign an Issue, Tags</a></li>
								<li><a href="<?php echo $admin_help_url; ?>#stories-add-thumbnail">Add a Thumbnail</a></li>
							</ul>
						</li>
						<li>
							<a href="<?php echo $admin_help_url; ?>#stories-adding-story-content">Adding Story Content</a>
							<ul>
								<li><a href="<?php echo $admin_help_url; ?>#stories-about-headings">About Headings and Structuring Content</a></li>
								<li><a href="<?php echo $admin_help_url; ?>#stories-add-headings">Adding Headings</a></li>
								<li><a href="<?php echo $admin_help_url; ?>#stories-add-media">Adding Media</a></li>
								<li><a href="<?php echo $admin_help_url; ?>#stories-add-shortcodes">Adding Content via Shortcodes</a></li>
								<li><a href="<?php echo $admin_help_url; ?>#stories-add-slideshow">Adding a Slideshow</a></li>
							</ul>
						</li>
						<li>
							<a href="<?php echo $admin_help_url; ?>#stories-customization">Story Subtitle, Description, and Customization</a>
						</li>
						<li>
							<a href="<?php echo $admin_help_url; ?>#stories-saving">Saving a Story</a>
						</li>
						<li>
							<a href="<?php echo $admin_help_url; ?>#stories-modifying">Modifying a Story</a>
						</li>
					</ul>

					<h3 id="stories-creating-stories">Creating Stories:</h3>
					<p>
						To create a story, hover over the “Stories” link in the left-hand WordPress admin menu, and click “Add New”.
					</p>

					<h4 id="stories-add-title">Add a Title</h4>
					<p>
						Enter the title of the story in the title field at the top of the screen.
					</p>

					<h4 id="stories-issue-tags">Assign an Issue, Tags</h4>
					<img src="<?php echo THEME_HELP_IMG_URL?>/story-tags-issue.jpg" />
					<p>
						On the right-hand side of the screen, select an Issue from the Issues metabox (select the season, i.e. Fall/Spring/Summer, and the year) and add any appropriate tags in the Tags metabox. When picking tags, think about words or phrases that you would use if you were to search for this story on Google.
					</p>

					<h4 id="stories-add-thumbnail">Add a Thumbnail</h4>
					<img src="<?php echo THEME_HELP_IMG_URL?>/story-featured-image.jpg" />
					<p>
						The story’s thumbnail should be uploaded as the story’s Featured Image. To do so, click the “Set featured image” link in the Featured Image metabox on the right side of the screen. Upload or select an image and click the “Set featured image” button.
					</p>

					<div class="well">
						<p><strong>Thumbnail Specs/Guidelines:</strong></p>
						<ul>
							<li>Story thumbnails should be <strong>1200x800px</strong></li>
							<li>Thumbnails can be <strong>.jpg or .pngs</strong>. Adding transparency is not recommended.</li>
							<li>
								Keep in mind that thumbnails are shrunk down to as low as 140px wide on some sections of the Pegasus website; omitting text on these images is recommended.
							</li>
							<li>
								Running thumbnails through an image compression service like <a href="https://imageoptim.com/mac" target="_blank">ImageOptim</a> before uploading is recommended.
							</li>
						</ul>
					</div>

					<h3 id="stories-adding-story-content">Adding Story Content:</h3>
					<p>
						To add content to a story, use the large text editor directly below the title field to type and/or paste content. Only body copy needs to be added here; the title, story description, content in the story's sidebar, and header image are handled separately.
					</p>
					<p>
						Most text is directly editable through the Visual editor. Make sure that you are in Visual editor mode when
						you’re adding content &mdash; if you don’t see a set of menu buttons above the editor that look like Microsoft Word text editor buttons, make sure the “Visual” tab (NOT the “Text” tab) on the top right of the editor is selected. The Visual editor lets you add and manipulate content similarly to how Microsoft Word does.
					</p>

					<h4 id="stories-about-headings">About Headings and Structuring Content</h4>
					<div class="well">
						<p><strong><em>
							Please read all of the guidelines below—they are very important and help us create content that is semantic, accessible and SEO-friendly!
						</em></strong></p>
					</div>
					<p>
						On the web, titles and subtitles for any article and its sections are structured in a hierarchical way. These titles are known as <strong>headings</strong>, and the code behind them allows for up to 6 different tiers of headings—<strong>heading 1 (h1) through heading 6 (h6)</strong>. This system allows us to define a "table of contents" for our story, so that screenreaders and search engines can understand the structure of our content. View the screenshot below for an example of what these headings are.
					</p>

					<img src="<?php echo THEME_HELP_IMG_URL?>/headings.png" />
					<p class="caption">
						A sample story with structured headings for each section and subsection.
					</p>

					<p>
						A <strong>h1</strong> is considered the most important title on the page—i.e., the title of the article. There should only ever be one h1 on a page at any given time—in our story templates, the h1 is automatically set as the story title, so you should <strong>never set any text in your body copy as an h1</strong>.
					</p>
					<p>
						<strong>h2's</strong> would be the next most important titles or section headings. h2's will be the first type of heading you use when declaring headings in your story content. h2's, as well as all other headings, should always describe the section of content that immediately follows after it.
					</p>
					<p>
						<strong>h3-h6</strong> continue the trend of importance; h6 is the most minor heading.
					</p>

					<div class="well">
						<p><strong>Notes on Headings:</strong></p>
						<ul>
							<li>
								<strong>Headings should always be assigned in order</strong>—i.e., do not place a h2 under a h3 unless you are defining a new, separate section of content. Do not skip headings either— i.e., don't jump from a h2 straight to a h4. <strong>Note: Your story should never use h3-6 without declaring a h2 first.</strong>
							</li>
							<li>
								Do NOT assign text a heading designation if it is not a heading! <strong>Headings should NOT be assigned to force a particular font size or style on a line of text.</strong> Style non-heading text with the "Formats" dropdown instead.
							</li>
							<li>
								Most of the time, you’ll probably not use h5's or h6's, but they are available if necessary. You might not use any headings beyond h2 in a story—that is okay, as long as the structure of the story makes sense with the headings you’ve used.
							</li>
						</ul>
					</div>

					<h4 id="stories-add-headings">Adding Headings</h4>
					<img src="<?php echo THEME_HELP_IMG_URL?>/story-add-heading.jpg" />
					<p>
						To add a heading or make an existing line of text a heading, highlight the text you want to modify in the editor, and from the “Paragraph” dropdown in the Kitchen Sink, select from the Heading 2 through Heading 6 options. This text will be styled using the font family and color selected in the Default Template Header Font Family/Color fields.
					</p>

					<h4 id="stories-add-media">Adding Media</h4>
					<p>
						Media (images, videos, and audio) can be uploaded to WordPress’s media library so that they are self-hosted. Generally, this is necessary for photos and video or audio that cannot be uploaded to an external service like Youtube.
					</p>
					<div class="well">
						<p>
							<strong>Note: if you need to add a Youtube, Vimeo, or other hosted video player, you do not need to upload any files;</strong> simply paste the URL of the video into the editor content on its own line, and WordPress will figure out the rest for you.
						</p>
					</div>
					<img src="<?php echo THEME_HELP_IMG_URL?>/story-add-media.jpg" />
					<p>
						Media can be added to the post content by clicking on the Add Media button under the story title.
					</p>
					<img src="<?php echo THEME_HELP_IMG_URL?>/story-media-modal.jpg" />
					<p>
						In the window that appears, you can either browse existing media or upload a new file. Click on a file’s thumbnail to select it (if you just uploaded the file, it’ll be selected for you.)
					</p>
					<p>
						The media’s details can then be modified on the right, under Attachment Details and Attachment Display Settings. For images, you will need to add a caption (optional) and alt text. The caption will be displayed below the image; alt text will appear when you hover over the image, and provides additional information about the image to screenreaders and search engines. Adjust the image’s position under the Alignment dropdown and the size of the image if necessary (note some images may not provide an image size option.) Keep in mind that if no image position is selected, the image will automatically be stretched to the full width of the post content column.
					</p>
					<p>
						Once you have filled out all the information, click the Insert into Post button. The media’s respective shortcode will then be placed where your cursor was last at in the editor.
					</p>

					<div class="well">
						<p><strong>Media Specs/Guidelines:</strong></p>
						<p><em>Images</em></p>
						<ul>
							<li>Images should be saved in RGB format (not CMYK)</li>
							<li>Image resolution should always be 72 dpi</li>
							<li>
								Images should never be larger than absolutely necessary—scale down/crop your images BEFORE uploading them to WordPress! Images that are too large slow down the website, which is bad for both the user experience and SEO.
							</li>
							<li>
								Choose a file format that best suits your image—generally, photographs are better suited as .jpg's; illustrations, logos, etc. are best suited as .png's.
							</li>
							<li>
								When saving out images in PhotoShop, use the Save for Web and Devices tool to compare compression rates and file types. When saving out .jpg's, try to apply some level of quality compression to reduce the size of the file. A quality of 65 for .jpg's is usually adequate. Transparent .png's should be saved out as PNG-24 with transparency enabled.
							</li>
							<li>
								Running images through an image compression tool such as <a href="https://imageoptim.com/mac" target="_blank">ImageOptim</a> is recommended.
							</li>
							<li>
								Keep in mind that images will be scaled down on small window sizes if they do not fit the width of the screen. Images with overlaid text should be tested for legibility by resizing your browser window on the live view of the story.
							</li>
						</ul>

						<p><em>Video</em></p>
						<ul>
							<li>
								Videos should be saved as <strong>.mp4</strong> for the best compatibility among desktop browsers and mobile devices.
							</li>
						</ul>

						<p><em>Audio</em></p>
						<ul>
							<li>
								Audio files should be saved as <strong>.mp3</strong> for the best compatibility among desktop browsers and mobile devices.
							</li>
						</ul>
					</div>

					<h4 id="stories-add-shortcodes">Adding Content via Shortcodes</h4>
					<p>
						Shortcodes are small snippets of code, wrapped in square brackets [], that do some function or add some
						predefined content to your story.
					</p>
					<p>
						On this site, custom shortcodes are available to create blockquotes, callout boxes, sidebars, slideshows, and
						other stylized content. Please visit the <a href="#shortcodes">Shortcodes documentation section</a> for
						information about what shortcodes are available and what options they provide.
					</p>
					<img src="<?php echo THEME_HELP_IMG_URL?>/story-add-shortcode.jpg" />
					<p>
						To add a shortcode to your story content, click the “Add Shortcode” button just under the title field.
					</p>
					<img src="<?php echo THEME_HELP_IMG_URL?>/story-shortcode-modal.jpg" />
					<p>
						In the window that appears, select a shortcode, and update any available options if necessary. Click the “Insert into
						Post” button to insert the shortcode where your cursor was last at in the editor.
					</p>

					<h4 id="stories-add-slideshow">Adding a Slideshow</h4>
					<p>
						A slideshow is a rotating set of images with optional captions. All slideshows use photos from an existing
						Photo Essay; make sure you’ve created a photo essay with your photos before trying to insert a slideshow shortcode.
					</p>
					<p>
						Slideshows are added by using the [slideshow] shortcode. See the documentation above on
						<a href="#stories-add-shortcodes">Adding Content via Shortcodes</a>, and the
						<a href="#shortcodes">Shortcodes documentation section</a> for more details.
					</p>
					<p>
						Slideshows can be added to story content in two ways—the entire story can be a full-screen slideshow, or a small
						slideshow (an "embedded" slideshow) can be added between other content.
					</p>
					<img src="<?php echo THEME_HELP_IMG_URL?>/story-photoessay-comparison.jpg" />
					<p class="caption">
						Note the differences between a story with an embedded slideshow, and a full-screen Photo Essay story. For full-screen
						Photo Essay stories, the Story Template field must be set to "Photo essay".
					</p>
					<p>
						<strong>If you’re creating a full-screen slideshow</strong>, the only content in the story editor should be the
						slideshow shortcode. After the shortcode is inserted in the story content, make sure to update the Story Template
						dropdown (in the Story Fields box, under the content editor) to use the Photo essay template.
					</p>

					<h3 id="stories-customization">Story Subtitle, Description, and Customization:</h3>
					<img src="<?php echo THEME_HELP_IMG_URL?>/story-fields.jpg" />
					<p>
						The Story Fields metabox, located underneath the story content editor, contains set of extra fields that add custom
						settings to your story. Most of these fields are straightforward and provide descriptions.
					</p>
					<div class="well">
						<p><strong>Things to keep in mind:</strong></p>
						<ul>
							<li>Stories that are not created by a developer should never use a “Custom” Story Template.</li>
							<li>
								The Default Template Header Font Family uses a pre-defined set of font sizes, line heights, and weights that
								best suit headings at all sizes. These pre-defined settings cannot be modified and cannot be set for other
								lines of text besides headings. Please do not use headings to style lines of text that are not actually headings;
								read the <a href="#stories-about-headings">About Headings and Structuring Content documentation section</a> for
								more information.
							</li>
							<li>
								The Default Template Header Image’s recommended dimensions (1600x900px) are relatively large for the web—please
								make sure your image is optimized for the web BEFORE uploading it to WordPress. See the
								<a href="#stories-add-media">Adding Media documentation section</a> for more information.
							</li>
							<li>
								The Default Template Header Image will be stretched and cropped at different window sizes. Please keep the
								composition of the image in mind when designing it—the top and bottom edges of the image will be cropped at some
								sizes, particularly mobile device size. Try to center the focus of the composition to the middle of the image.
								Before publishing your story, upload the Header Image and test what it looks like in Preview mode by resizing your
								browser window on the front-facing story.
							</li>
						</ul>
					</div>

					<h3 id="stories-saving">Saving a Story:</h3>
					<img src="<?php echo THEME_HELP_IMG_URL?>/story-save.jpg" />
					<p>
						To save any changes to your story, click the gray "Save Draft" button on the right-hand side of the screen, in the Publish
						metabox.
					</p>
					<p>
						If your story is finished and you have tested it by clicking the "Preview" button in the Publish metabox, publish it by
						clicking the blue "Publish" button in the Publish metabox.
					</p>

					<h3 id="stories-modifying">Modifying a Story:</h3>
					<p>
						To modify an existing story, click on "Stories" in the left-hand WordPress admin menu. You can modify any stories that
						have been created by you by clicking the title of the story.
					</p>
					<p>
						When modifying a story, you'll be presented with the same screen that you originally created the post with. Modify any content
						as necessary and click the "Update" button in the Publish metabox to finish. <strong>Make sure you click the "Update"
						button to save your changes!</strong>
					</p>

				</li>

				<li class="section" id="photo-essays">
					<h2>Photo Essays</h2>
					<p>
						A <strong>Photo Essay</strong> is an ordered set of photos and captions.
					</p>

					<p><strong>Navigation:</strong></p>
					<ul class="section-nav">
						<li>
							<a href="<?php echo $admin_help_url; ?>#photo-essays-creating">Creating Photo Essays</a>
							<ul>
								<li><a href="<?php echo $admin_help_url; ?>#photo-essays-add-title">Add a Title</a></li>
								<li><a href="<?php echo $admin_help_url; ?>#photo-essays-issue">Assign an Issue</a></li>
							</ul>
						</li>
						<li>
							<a href="<?php echo $admin_help_url; ?>#photo-essays-creating-slides">Creating Slides</a>
							<ul>
								<li><a href="<?php echo $admin_help_url; ?>#photo-essays-slide-content">Title and Caption</a></li>
								<li><a href="<?php echo $admin_help_url; ?>#photo-essays-requirements">Requirements</a></li>
								<li><a href="<?php echo $admin_help_url; ?>#photo-essays-order-slides">Ordering Slides</a></li>
								<li><a href="<?php echo $admin_help_url; ?>#photo-essays-delete-slide">Delete a Slide</a></li>
							</ul>
						</li>
						<li>
							<a href="<?php echo $admin_help_url; ?>#photo-essays-save">Saving a Photo Essay</a>
						</li>
					</ul>


					<h3 id="photo-essays-creating">Creating Photo Essays</h3>
					<p>
						To create a photo essay, hover over the “Photo Essays” link in the left-hand WordPress admin menu, and click “Add New”.
					</p>

					<h4 id="photo-essays-add-title">Add a Title</h4>
					<p>
						Enter the title of the entire photo essay in the title field at the top of the screen. Note that individual slides
						also have their own titles.
					</p>

					<h4 id="photo-essays-issue">Assign an Issue</h4>
					<img src="<?php echo THEME_HELP_IMG_URL?>/photo_essay-issue.jpg" />
					<p>
						On the right-hand side of the screen, select an Issue from the Issues metabox (select the season, i.e.
						Fall/Spring/Summer, AND the year.)
					</p>


					<h3 id="photo-essays-creating-slides">Creating Slides</h3>
					<img src="<?php echo THEME_HELP_IMG_URL?>/photo_essay-create-slides.jpg" />
					<p>
						To create your first slide(s), click the "Create New Slides" button under the WYSIWYG editor.
					</p>
					<p>
						The window that appears is just like the window that you select media from when creating stories. You can either upload new images under the "Upload Files" section, or select one or more existing images from the Media Library.
					</p>
					<p>
						To select more than one image from the Media Library for slide creation, click the first thumbnail in the set you want to select, then hold the Shift key and click the last thumbnail in the set. All the thumbnails between the first and last thumbnail will have a checkbox icon next to them in the top-right hand corner.
					</p>
					<p>
						To create your slides, click the "Create New Slides" button at the bottom of the window. The Media Library window will close, and any image with a checkbox next to it will be inserted as a slide. New slides are added at the bottom of the list of existing slides.
					</p>

					<h4 id="photo-essays-slide-content">Caption</h4>
					<p>
						Captions can be added for each image and will appear either below or to the right of the image depending on the image's orientation.
					</p>
					<p>
						Keep in mind that setting the caption here will not modify the corresponding image caption in the Media Library.
					</p>

					<div class="well">
						<h4 id="photo-essays-requirements">Requirements</h4>
						<ul>
							<li>
								<strong>Each photo essay slide requires a caption and image.</strong> If both of these are not set for any slide, that slide will not be saved when the photo essay is saved/published.
							</li>
							<li>
								All images uploaded to photo essays should be <strong>web-optimized</strong>! Please see the Media Specs/Guidelines section of the <a href="#stories">Story documentation</a> for basic image optimization requirements.
							</li>
							<li>
								<strong>All images uploaded to a single photo essay must be the same height</strong>. Widths can vary, but the height must be consistent throughout all images.
							</li>
						</ul>
					</div>

					<h4 id="photo-essays-order-slides">Ordering Slides</h4>
					<img src="<?php echo THEME_HELP_IMG_URL?>/photo_essay-move.jpg" />
					<p>
						To re-order a slide, click and drag the top bar of the slide's metabox up or down to re-order it.
					</p>

					<h4 id="photo-essays-delete-slide">Delete a Slide</h4>
					<img src="<?php echo THEME_HELP_IMG_URL?>/photo_essay-delete-slide.jpg" />
					<p>
						To delete a single slide, click the "Remove Slide" button on the bottom-left of any single slide metabox. Note that this button will not appear if only one slide is left on the screen. Also note that this does not delete the image from the Media Library.
					</p>
					<div class="well">
						<p>
							<strong>NOTE:</strong> If an image has been deleted from the Media Library but is still saved as a slide in a photo essay, <strong>that slide will display an empty or broken image on the slideshow it generates.</strong> The slide created from the deleted image will have to be deleted manually to fix this.
						</p>
					</div>


					<h3 id="photo-essays-save">Saving a Photo Essay</h3>
					<img src="<?php echo THEME_HELP_IMG_URL?>/photo_essay-save.jpg" />
					<p>
						To save any changes to your story, click the gray "Save Draft" button on the right-hand side of the screen, in the Publish metabox.
					</p>
					<div class="well">
						<p>
							<strong>Note: auto-saving has been disabled for Photo Essays. To use the "Preview" button, you MUST save your post as a draft first! This must be done with any set of revisions &mdash; even if the post has been published already.</strong>
						</p>
					</div>
					<p>
						If your photo essay is finished and you have tested it by clicking the "Preview" button in the Publish metabox, publish it by clicking the blue "Publish" button in the Publish metabox.
					</p>
				</li>


				<li class="section" id="shortcodes">
					<h2>Shortcodes</h2>
					<p>
						<strong>Shortcodes</strong>, in a nutshell, are <em>shortcuts</em> for displaying or doing various things. They look like small snippets of code, wrapped in square brackets [], but using them requires no knowledge of HTML, CSS, or other code languages.
					</p>

					<h3 id="shortcodes-basics">Shortcode Basics</h3>

					<p>
						When a shortcode is added to post content, it will be displayed in the editor as a code snippet, but when you view the post as a preview or live post, you will see the output of what the shortcode is programmed to do, with the <strong>content</strong> and <strong>attributes</strong> you provide.
					</p>
					<p>
						The Pegasus site is now utilizing Athena Shortcodes, powered by our Athena Shortcodes Plugin. See the <a href="https://github.com/UCF/Athena-Shortcodes-Plugin/wiki" target="_blank">Athena Shortcodes Plugin wiki</a> for information on what shortcodes are available to use and instructions on how to use them.
					</p>
				</li>
			</ul>
		</div>
	</div>
</div>
