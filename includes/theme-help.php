<div id="theme-help" class="i-am-a-fancy-admin">
	<div class="container">
		<h1>Pegasus Magazine Site Help - for Content Creators</h1>

		<?php if ($updated):?>
		<div class="updated fade"><p><strong><?=__( 'Options saved' ); ?></strong></p></div>
		<?php endif; ?>

		<div class="sections">
			<ul>
				<li class="section"><a href="#intro">Intro</a></li>
				<li class="section"><a href="#stories">Stories</a></li>
				<li class="section"><a href="#issues">Issues</a></li>
				<li class="section"><a href="#photo-essays">Photo Essays</a></li>
				<li class="section"><a href="#shortcodes">Shortcodes</a></li>
			</ul>
		</div>
		<div class="fields">
			<ul>
				<li class="section" id="intro">
					<h2>Intro</h2>
					<p>
						The goal of the help section is to familiarize yourself with the Pegasus website and the
						different types of content that can be created. This should also help you understand the flow
						of content creation for Pegasus.
					</p>
					<p>
						Keep in mind that this help section assumes a basic working knowledge of the WordPress admin interface.  
						If you are unfamiliar with WordPress, please 
						<a href="http://code.tutsplus.com/series/wp101-basix-training--wp-20968" target="_blank">check out a few tutorials</a> 
						before getting started.
					</p>
					<br/>
					<p>
						The primary types of content that can be created in this site are <strong>Issues</strong>, <strong>Stories</strong>, and 
						<strong>Photo Essays</strong>.
					<p>
						<strong>Issues</strong> are a means of categorizing a group of Stories much like the physical magazine.  Issues
						also exist as a type of post, which serves as the landing page for the website when that Issue is active.
					</p>
					<p>
						<strong>Stories</strong> are where the meat of the content will reside. The Stories that will
						live on the website are hand picked from the physical magazine.
					</p>
					<p>
						The <strong>Photo Essay</strong> is a type of content used to create a slideshow of images with captions in a Story 
						or Issues.  Photo Essays can be embedded onto Stories or Issues using a <strong>Shortcode</strong>.
					</p>
					<br/>
					<p>
						<strong>Shortcodes</strong> are small snippets of code, wrapped in square brackets [], that do some function or add some 
						predefined content to your story content.  On this site, we use them to add blockquotes, callout boxes, sidebars, and 
						more to Story and Issue content.
					</p>
				</li>

				<li class="section" id="issues">
					<h2>Issues</h2>

					<p>
						<strong>Issues</strong> are used to categorize Stories just like in the physical magazine.
						They consist of a cover story and a listing of the other Stories related to this issue.
					</p>
					<p>
						The titles follow a year and semester format (ex. Fall 2013, Summer 2014, etc). Below the
						Issue content area you will be able to set values for various pieces of the
						page (descriptions below).
					</p>
					<p>
						<ul>
							<li><strong>Cover Story:</strong> This will be the headline story for the Issue.</li>
							<li><strong>Issue Template:</strong> Give the ability to set a custom template to use (rarely used for consistancy purposes).</li>
							<li><strong>Header Font Family:</strong> This will set the font family for all the headers (h1-h6) on the Issue page.</li>
							<li><strong>Header Font Color:</strong> Sets a color to all the headers (h1-h6) on the Issue page.</li>
							<li><strong>Header Font Size (Desktop, Tablet, and Mobile):</strong> This sets the font size to use for the headers (h1-h6) based on the device the user is on.</li>
							<li><strong>Header Font Text Align:</strong> Sets the alignment of the Issue header (h1-h6) to left, center or right.</li>
							<li><strong>Home Page Stylesheet:</strong> Only used when developing a custom Issue page.</li>
							<li><strong>Home Page JavaScript File:</strong> Only used when developing a custom Issue page.</li>
						</ul>
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
							<a href="#stories-creating-stories">Creating Stories</a>
							<ul>
								<li><a href="#stories-add-title">Add a Title</a></li>
								<li><a href="#stories-issue-tags">Assign an Issue, Tags</a></li>
								<li><a href="#stories-add-thumbnail">Add a Thumbnail</a></li>
							</ul>
						</li>
						<li>
							<a href="#stories-adding-story-content">Adding Story Content</a>
							<ul>
								<li><a href="#stories-about-headings">About Headings and Structuring Content</a></li>
								<li><a href="#stories-add-headings">Adding Headings</a></li>
								<li><a href="#stories-manipulating-text">Manipulating and Styling Text</a></li>
								<li><a href="#stories-add-media">Adding Media</a></li>
								<li><a href="#stories-add-shortcodes">Adding Content via Shortcodes</a></li>
								<li><a href="#stories-add-slideshow">Adding a Slideshow</a></li>
							</ul>
						</li>
						<li>
							<a href="#stories-customization">Story Subtitle, Description, and Customization</a>
						</li>
						<li>
							<a href="#stories-saving">Saving a Story</a>
						</li>
						<li>
							<a href="#stories-modifying">Modifying a Story</a>
						</li>
					</ul>

					<h3 id="stories-creating-stories">Creating Stories:</h3>
					<p>
						To create a story, hover over the “Stories” link in the left-hand WordPress admin menu, and click “Add New”.
					</p>

					<h4 id="stories-add-title">Add a Title</h4>
					<p>
						Enter the title of the story in the title field at the top of the screen.  Keep in mind that custom font sizes,
						colors, or colors cannot be applied to the story title.
					</p>

					<h4 id="stories-issue-tags">Assign an Issue, Tags</h4>
					<img src="<?=THEME_HELP_IMG_URL?>/story-tags-issue.jpg" />
					<p>
						On the right-hand side of the screen, select an Issue from the Issues metabox (select the season, i.e. 
						Fall/Spring/Summer, and the year) and add any appropriate tags in the Tags metabox.  When picking 
						tags, think about words or phrases that you would use if you were to search for this story on Google.
					</p>

					<h4 id="stories-add-thumbnail">Add a Thumbnail</h4>
					<img src="<?=THEME_HELP_IMG_URL?>/story-featured-image.jpg" />
					<p>
						The story’s thumbnail should be uploaded as the story’s Featured Image.  To do so, click the “Set featured 
						image” link in the Featured Image metabox on the right side of the screen.  Upload or select an image as 
						usual and click the “Set featured image” button.
					</p>

					<div class="well">
						<p><strong>Thumbnail Specs/Guidelines:</strong></p>
						<ul>
							<li>Story thumbnails should be <strong>220x230px</strong></li>
							<li>Thumbnails can be <strong>.jpg or .png’s</strong>.  Adding transparency is not recommended.</li>
							<li>
								Keep in mind that thumbnails are shrunk down to as low as 140px wide on some sections of the Pegasus 
								website; make sure that any text on the thumbnails is legible at small sizes.
							</li>
							<li>
								Running thumbnails through an image compression service like 
								<a href="http://imageoptim.com/" target="_blank">ImageOptim</a> is recommended.
							</li>
						</ul>
					</div>

					<h3 id="stories-adding-story-content">Adding Story Content:</h3>
					<img src="<?=THEME_HELP_IMG_URL?>/story-content-editor.jpg" />
					<p>
						To add content to a story, use the large text editor directly below the title field to type and paste content.  
						Only body copy needs to be added here; the title, story description, and header image are handled separately.  
						When pasting content from Microsoft Word, be sure to click the "Paste from Word" icon to avoid pasting in
						non-web-friendly text formatting.
					</p>
					<p>
						Most text is directly editable through the Visual editor.  Make sure that you are in Visual editor mode when 
						you’re adding content—if you don’t see a set of menu buttons above the editor that look like Microsoft Word
						text editor buttons, make sure the “Visual” tab (NOT the “Text” tab) on the top right of the editor is selected.  
						The Visual editor lets you add and manipulate content similarly to how Microsoft Word does.
					</p>

					<h4 id="stories-about-headings">About Headings and Structuring Content</h4>
					<div class="well">
						<p><strong><em>
							Please read all of the guidelines below—they are very important and help us create content that is semantic, 
							accessible and SEO-friendly!
						</em></strong></p>
					</div>
					<p>
						On the web, titles and subtitles for any article and its sections are structured in a hierarchical way.  These 
						titles are known as <strong>headings</strong>, and the code behind them allows for up to 6 different tiers of 
						headings—<strong>heading 1 (h1) through heading 6 (h6)</strong>.  This system allows us to define a "table of 
						contents" for our story, so that screenreaders and search engines can understand the structure of our
						content.  View the screenshot below for an example of what these headings are.
					</p>

					<img src="<?=THEME_HELP_IMG_URL?>/headings.png" />
					<p class="caption">
						A sample story with structured headings for each section and subsection.
					</p>

					<p>
						A <strong>h1</strong> is considered the most important title on the page—i.e., the title of the article.  There 
						should only ever be one h1 on a page at any given time—in our story templates, the h1 is automatically set as 
						the story title, so you should <strong>never set any text in your body copy as an h1</strong>.
					</p>
					<p>
						<strong>h2's</strong> would be the next most important titles or section headings.  h2's will be the first type of
						heading you use when declaring headings in your story content.  h2's, as well as all other headings, should always 
						describe the section of content that immediately follows after it.
					</p>
					<p>
						<strong>h3-h6</strong> continue the trend of importance; h6 is the most minor heading.
					</p>

					<div class="well">
						<p><strong>Notes on Headings:</strong></p>
						<ul>
							<li>
								<strong>Headings should always be assigned in order</strong>—i.e., do not place a h2 under a h3 unless you are 
								defining a new, separate section of content.  Do not skip headings either— i.e., don't jump from a h2 straight 
								to a h4.  <strong>Note: Your story should never use h3-6 without declaring a h2 first.</strong>
							</li>
							<li>
								Do NOT assign text a heading designation if it is not a heading!  <strong>Headings should NOT be 
								assigned to force a particular font size or style on a line of text.</strong>  Style non-heading text with the
								"Styles" dropdown instead.
							</li>
							<li>
								Most of the time, you’ll probably not use h5's or h6's, but they are available if necessary.  You might not use 
								any headings beyond h2 in a story—that is okay, as long as the structure of the story makes sense with the 
								headings you’ve used.
							</li>
						</ul>
					</div>

					<h4 id="stories-add-headings">Adding Headings</h4>
					<img src="<?=THEME_HELP_IMG_URL?>/story-add-heading.jpg" />
					<p>
						To add a heading or make an existing line of text a heading, highlight the text you want to modify in the editor, 
						and from the “Paragraph” dropdown in the Kitchen Sink, select from the Heading 2 through Heading 6 options.  This 
						text will be styled using the font family and color selected in the Default Template Header Font Family/Color 
						fields.
					</p>

					<h4 id="stories-manipulating-text">Manipulating and Styling Text</h4>
					<p>
						The WordPress content editor (the "WYSIWYG" editor) provides a "Kitchen Sink" of tools that allow you to modify text styles.  
						Most of these tools are pretty straightforward and should look similar to Microsoft Word.  Below is a brief overview of 
						how to achieve various effects with these tools:
					</p>

					<img src="<?=THEME_HELP_IMG_URL?>/story-kitchen-sink.jpg" />

					<p><strong>Font Family</strong></p>
					<p>
						To modify the font family for a small chunk of text, use the "Styles" dropdown in the
						2nd row of the Kitchen Sink.  Here, you can pick from some of the most commonly used fonts in the print magazine, as well as
						fonts used in previous online editions.  Highlight the text you want to modify and pick the font to apply your changes.
					</p>
					<p>
						A custom font family can be applied to any small chunk of text, including headings (for one-off cases only.)
					</p>
					<p>
						Your custom font can be further customized with other Kitchen Sink tools, like the color selector and "Font size" dropdown.
					</p>
					<div class="well">
						<p><strong>Notes About Using Different Font Families:</strong></p>
						<ul>
							<li>
								<strong>Note: DO NOT use this tool to create your story's titles and subtitles!  Use
								<a href="#stories-about-headings">headings</a> instead.</strong>
							</li>
							<li>
								Do not apply a custom font family for your story's entire body copy.  This tool is intended for small groups of text
								only.  Applying a custom font family to the entire story can potentially slow down the load time of the story.
							</li>
							<li>
								Avoid applying bold/italic styles on custom fonts; use the provided italic/bold font alternatives for your font
								family instead.  Our web fonts are not optimized for italic/bold customization in this manner.
							</li>
						</ul>
					</div>

					<p><strong>Font Size</strong></p>
					<p>
						To modify the font size for a small chunk of text, use the "Font size" dropdown in the
						2nd row of the Kitchen Sink.  You can select from a range of sizes from 10px to 62px.
					</p>
					<p>
						Font size can be applied to any text, including headings (though this is not recommended unless absolutely necessary.)
					</p>
					<div class="well">
						<p><strong>Notes About Using Different Font Sizes:</strong></p>
						<ul>
							<li>
								<strong>Note: DO NOT use this tool to create your story's titles and subtitles!  Use
								<a href="#stories-about-headings">headings</a> instead.</strong>
							</li>
							<li>
								Do not apply a custom font size for your story's entire body copy.  This tool is intended for small groups of text
								only.
							</li>
						</ul>
					</div>

					<p><strong>Font Color</strong></p>
					<p>
						To modify the font color for a small chunk of text, use the font color selector in the Kitchen Sink (to the right of the 
						"Paragraph" dropdown.)  To specify a custom hex value, click the "More Colors" button at the bottom of the box that appears.
					</p>

					<h4 id="stories-add-media">Adding Media</h4>
					<p>
						Media (images, videos, and audio) can be uploaded to WordPress’s media library so that they are self-hosted.  
						Generally, this is necessary for photos and video or audio that cannot be uploaded to an external service like 
						Youtube.
					</p>
					<div class="well">
						<p>
							<strong>Note: if you need to add a Youtube, Vimeo, or other hosted video player, you do not 
							need to upload any files;</strong> simply paste the URL of the video into the editor content on its own line, and WordPress 
							will figure out the rest for you.
						</p>
					</div>
					<img src="<?=THEME_HELP_IMG_URL?>/story-add-media.jpg" />
					<p>
						Media can be added to the post content by clicking on the Add Media button under the story title.
					</p>
					<img src="<?=THEME_HELP_IMG_URL?>/story-media-modal.jpg" />
					<p>
						In the window that appears, you can either browse existing media or upload a new file.  Click on a file’s thumbnail 
						to select it (if you just uploaded the file, it’ll be selected for you.)
					</p>
					<p>
						The media’s details can then be modified on the right, under Attachment Details and Attachment Display Settings.  
						For images, you will need to add a caption (optional) and alt text.  The caption will be displayed below the image; 
						alt text will appear when you hover over the image, and provides additional information about the image to 
						screenreaders and search engines.  Adjust the image’s position under the Alignment dropdown and the size of the 
						image if necessary (note some images may not provide an image size option.)  Keep in mind that if no image position 
						is selected, the image will automatically be stretched to the full width of the post content column.
					</p>
					<p>
						Once you have filled out all the information, click the Insert into Post button.  The media’s respective shortcode 
						will then be placed where your cursor was last at in the editor.
					</p>

					<div class="well">
						<p><strong>Media Specs/Guidelines:</strong></p>
						<p><em>Images</em></p>
						<ul>
							<li>Images should be saved in RGB format (not CMYK)</li>
							<li>Image resolution should always be 72 dpi</li>
							<li>
								Images should never be larger than absolutely necessary—scale down/crop your images BEFORE uploading them to 
								WordPress!  Images that are too large slow down the website, which is bad for both the user experience and SEO.
							</li>
							<li>
								Choose a file format that best suits your image—generally, photographs are better suited as .jpg's; illustrations, 
								logos, etc. are best suited as .png's.
							</li>
							<li>
								When saving out images in PhotoShop, use the Save for Web and Devices tool to compare compression rates and file 
								types.  When saving out .jpg's, try to apply some level of quality compression to reduce the size of the file.  
								A quality of 65 for .jpg's is usually adequate.  Transparent .png's should be saved out as PNG-24 with transparency 
								enabled.
							</li>
							<li>
								Running images through an image compression tool such as 
								<a href="http://imageoptim.com/" target="_blank">ImageOptim</a> is recommended, but not required.
							</li>
							<li>
								Keep in mind that images will be scaled down on small window sizes if they do not fit the width of the screen.  
								Images with overlaid text should be tested for legibility by resizing your browser window on the live view of 
								the story.
							</li>
						</ul>

						<p><em>Video</em></p>
						<ul>
							<li>
								Videos should be saved as <strong>.mp4</strong> for the best compatibility among desktop browsers and mobile 
								devices.
							</li>
							<li>
								Extra file formats for backward compatibility with old browsers can be added to the shortcode after it is 
								inserted into the post; see the 
								<a href="https://codex.wordpress.org/Video_Shortcode" target="_blank">WordPress video shortcode documentation</a> 
								for details.
							</li>
						</ul>

						<p><em>Audio</em></p>
						<ul>
							<li>
								Audio files should be saved as <strong>.mp3</strong> for the best compatibility among desktop browsers and 
								mobile devices.
							</li>
							<li>
								Extra file formats for backward compatibility with old browsers can be added to the shortcode after it is 
								inserted into the post; see the 
								<a href="https://codex.wordpress.org/Audio_Shortcode" target="_blank">WordPress audio shortcode documentation</a> 
								for details.
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
						other stylized content.  Please visit the <a href="#shortcodes">Shortcodes documentation section</a> for 
						information about what shortcodes are available and what options they provide.
					</p>
					<img src="<?=THEME_HELP_IMG_URL?>/story-add-shortcode.jpg" />
					<p>
						To add a shortcode to your story content, click the “Add Shortcode” button just under the title field.
					</p>
					<img src="<?=THEME_HELP_IMG_URL?>/story-shortcode-modal.jpg" />
					<p>
						In the window that appears, select a shortcode, and update any available options if necessary.  Click the “Insert into 
						Post” button to insert the shortcode where your cursor was last at in the editor.
					</p>

					<h4 id="stories-add-slideshow">Adding a Slideshow</h4>
					<p>
						A slideshow is a rotating set of images with optional captions.  All slideshows use photos from an existing 
						Photo Essay; make sure you’ve created a photo essay with your photos before trying to insert a slideshow shortcode.
					</p>
					<p>
						Slideshows are added by using the [slideshow] shortcode.  See the documentation above on 
						<a href="#stories-add-shortcodes">Adding Content via Shortcodes</a>, and the 
						<a href="#shortcodes">Shortcodes documentation section</a> for more details.
					</p>
					<p>
						Slideshows can be added to story content in two ways—the entire story can be a full-screen slideshow, or a small 
						slideshow (an "embedded" slideshow) can be added between other content.
					</p>
					<img src="<?=THEME_HELP_IMG_URL?>/story-photoessay-comparison.jpg" />
					<p class="caption">
						Note the differences between a story with an embedded slideshow, and a full-screen Photo Essay story.  For full-screen
						Photo Essay stories, the Story Template field must be set to "Photo essay".
					</p>
					<p>
						<strong>If you’re creating a full-screen slideshow</strong>, the only content in the story editor should be the 
						slideshow shortcode.  After the shortcode is inserted in the story content, make sure to update the Story Template 
						dropdown (in the Story Fields box, under the content editor) to use the Photo essay template.
					</p>

					<h3 id="stories-customization">Story Subtitle, Description, and Customization:</h3>
					<img src="<?=THEME_HELP_IMG_URL?>/story-fields.jpg" />
					<p>
						The Story Fields metabox, located underneath the story content editor, contains set of extra fields that add custom 
						settings to your story.  Most of these fields are straightforward and provide descriptions.
					</p>
					<div class="well">
						<p><strong>Things to keep in mind:</strong></p>
						<ul>
							<li>Stories that are not created by a developer should never use a “Custom” Story Template.</li>
							<li>
								The Default Template Header Font Family uses a pre-defined set of font sizes, line heights, and weights that 
								best suit headings at all sizes.  These pre-defined settings cannot be modified and cannot be set for other 
								lines of text besides headings.  Please do not use headings to style lines of text that are not actually headings; 
								read the <a href="#stories-about-headings">About Headings and Structuring Content documentation section</a> for 
								more information.
							</li>
							<li>
								The Default Template Header Image’s recommended dimensions (1600x900px) are relatively large for the web—please 
								make sure your image is optimized for the web BEFORE uploading it to WordPress.  See the 
								<a href="#stories-add-media">Adding Media documentation section</a> for more information.
							</li>
							<li>
								The Default Template Header Image will be stretched and cropped at different window sizes.  Please keep the 
								composition of the image in mind when designing it—the top and bottom edges of the image will be cropped at some 
								sizes, particularly mobile device size.  Try to center the focus of the composition to the middle of the image.  
								Before publishing your story, upload the Header Image and test what it looks like in Preview mode by resizing your 
								browser window on the front-facing story.
							</li>
						</ul>
					</div>

					<h3 id="stories-saving">Saving a Story:</h3>
					<img src="<?=THEME_HELP_IMG_URL?>/story-save.jpg" />
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
						To modify an existing story, click on "Stories" in the left-hand WordPress admin menu.  You can modify any stories that
						have been created by you by clicking the title of the story.
					</p>
					<p>
						When modifying a story, you'll be presented with the same screen that you originally created the post with.  Modify any content
						as necessary and click the "Update" button in the Publish metabox to finish.  <strong>Make sure you click the "Update" 
						button to save your changes!</strong>
					</p>

				</li>

				<li class="section" id="photo-essays">
					<h2>Photo Essays</h2>
					<p>
						A <strong>Photo Essay</strong> is an ordered set of photos and captions.  They exist for creating embedded or full-screen
						<strong>slideshows</strong> with the [slideshow] shortcode.
					</p>

					<p><strong>Navigation:</strong></p>
					<ul class="section-nav">
						<li>
							<a href="#photo-essays-creating">Creating Photo Essays</a>
							<ul>
								<li><a href="#photo-essays-add-title">Add a Title</a></li>
								<li><a href="#photo-essays-issue">Assign an Issue</a></li>
							</ul>
						</li>
						<li>
							<a href="#photo-essays-creating-slides">Creating Slides</a>
							<ul>
								<li><a href="#photo-essays-slide-content">Title and Caption</a></li>
								<li><a href="#photo-essays-requirements">Requirements</a></li>
								<li><a href="#photo-essays-order-slides">Ordering Slides</a></li>
								<li><a href="#photo-essays-delete-slide">Delete a Slide</a></li>
							</ul>
						</li>
						<li>
							<a href="#photo-essays-save">Saving a Photo Essay</a>
						</li>
					</ul>


					<h3 id="photo-essays-creating">Creating Photo Essays</h3>
					<p>
						To create a photo essay, hover over the “Photo Essays” link in the left-hand WordPress admin menu, and click “Add New”.
					</p>

					<h4 id="photo-essays-add-title">Add a Title</h4>
					<p>
						Enter the title of the entire photo essay in the title field at the top of the screen.  Note that individual slides 
						also have their own titles.
					</p>

					<h4 id="photo-essays-issue">Assign an Issue</h4>
					<img src="<?=THEME_HELP_IMG_URL?>/photo_essay-issue.jpg" />
					<p>
						On the right-hand side of the screen, select an Issue from the Issues metabox (select the season, i.e. 
						Fall/Spring/Summer, AND the year.)
					</p>


					<h3 id="photo-essays-creating-slides">Creating Slides</h3>
					<img src="<?=THEME_HELP_IMG_URL?>/photo_essay-create-slides.jpg" />
					<p>
						The Photo Essay editor has been revamped to allow for faster and more efficient slide creation.  To create your first slide(s),
						click the "Create New Slides" button at the top of the screen.
					</p>
					<img src="<?=THEME_HELP_IMG_URL?>/photo_essay-create-modal.jpg" />
					<p>
						The window that appears is just like the window that you select media from when creating stories.  You can either upload new
						images under the "Upload Files" section, or select one or more existing images from the Media Library.
					</p>
					<img src="<?=THEME_HELP_IMG_URL?>/photo_essay-modal-select.jpg" />
					<p>
						To select more than one image from the Media Library for slide creation, click the first thumbnail in the set you want to select, 
						then hold the Shift key and click the last thumbnail in the set.  All the thumbnails between the first and last thumbnail will 
						have a checkbox icon next to them in the top-right hand corner.
					</p>
					<p>
						Titles and captions for slides can be added or modified in this window.  Keep in mind that modifying a title or caption here will
						modify it anywhere else on the site if that same image is used elsewhere.  Custom, per-slide overrides can be added later.
					</p>
					<p>
						To create your slides, click the "Create New Slides" button at the bottom of the window.  The Media Library window will close, and
						any image with a checkbox next to it will be inserted as a slide.  New slides are added at the bottom of the list of existing slides.
					</p>

					<h4 id="photo-essays-slide-content">Title and Caption</h4>
					<img src="<?=THEME_HELP_IMG_URL?>/photo_essay-title-caption.jpg" />
					<p>
						When a slide is created from an image in the Media Library, whatever title and caption were set for that image are pulled
						automatically as the default title and caption for that slide.  To override these without modifying the title/caption of the 
						image in the Media Library, just change the Title and Caption fields for the slide after it's been created.
					</p>
					<p>
						Captions modified within the slide can use a mini WYSIWYG editor to apply bold, italics, or underline to text, and also add links.
					</p>
					<p>
						Keep in mind that anything saved here will not modify the corresponding image title/caption in the Media Library.  Modify the image
						from the Media Library instead to change those values.
					</p>

					<div class="well">
						<h4 id="photo-essays-requirements">Requirements</h4>
						<ul>
							<li>
								<strong>Each photo essay slide requires a title and image.</strong>  If both of these are not set for any slide, that 
								slide will not be saved when the photo essay is saved/published.
							</li>
							<li>
								All images uploaded to photo essays should be <strong>web-optimized</strong>!  Please see the Media Specs/Guidelines 
								section of the <a href="#stories">Story documentation</a> for basic image optimization requirements.
							</li>
							<li>
								<strong>All images uploaded to a single photo essay must be the same height</strong>.  Widths can vary, but the height 
								must be consistent throughout all images.
							</li>
						</ul>
					</div>

					<h4 id="photo-essays-order-slides">Ordering Slides</h4>
					<img src="<?=THEME_HELP_IMG_URL?>/photo_essay-move.jpg" />
					<p>
						To re-order a slide, click and drag the top bar of the slide's metabox up or down to re-order it.
					</p>

					<h4 id="photo-essays-delete-slide">Delete a Slide</h4>
					<img src="<?=THEME_HELP_IMG_URL?>/photo_essay-delete-slide.jpg" />
					<p>
						To delete a single slide, click the "Remove Slide" button on the bottom-left of any single slide metabox.  Note that this
						button will not appear if only one slide is left on the screen.  Also note that this does not delete the image from the Media
						Library.
					</p>
					<div class="well">
						<p>
							<strong>NOTE:</strong> If an image has been deleted from the Media Library but is still saved as a slide in a photo essay,
							<strong>that slide will display an empty or broken image on the slideshow it generates.</strong>  The slide created from
							the deleted image will have to be deleted manually to fix this.
						</p>
					</div>


					<h3 id="photo-essays-save">Saving a Photo Essay</h3>
					<img src="<?=THEME_HELP_IMG_URL?>/photo_essay-save.jpg" />
					<p>
						To save any changes to your story, click the gray "Save Draft" button on the right-hand side of the screen, in the Publish
						metabox.
					</p>
					<div class="well">
						<p>
							<strong>Note: auto-saving has been disabled for Photo Essays.  To use the "Preview" button, you MUST save your post as a
							draft first!  This must be done with any set of revisions--even if the post has been published already.</strong>
						</p>
					</div>
					<p>
						If your photo essay is finished and you have tested it by clicking the "Preview" button in the Publish metabox, publish it by
						clicking the blue "Publish" button in the Publish metabox.
					</p>
				</li>


				<li class="section" id="shortcodes">
					<h2>Shortcodes</h2>
					<p>
						<strong>Shortcodes</strong>, in a nutshell, are <em>shortcuts</em> for displaying or doing various things.  They look like small snippets of code, 
						wrapped in square brackets [], but using them requires no knowledge of HTML, CSS, or other code languages.
					</p>

					<p><strong>Navigation:</strong></p>
					<ul class="section-nav">
						<li>
							<a href="#shortcodes-basics">Shortcode Basics</a>
						</li>
						<li>
							<a href="#shortcodes-blockquote">Blockquote</a>
						</li>
						<li>
							<a href="#shortcodes-callout">Callout</a>
						</li>
						<li>
							<a href="#shortcodes-divider">Divider</a>
						</li>
						<li>
							<a href="#shortcodes-lead">Lead</a>
						</li>
						<li>
							<a href="#shortcodes-sidebar">Sidebar</a>
						</li>
						<li>
							<a href="#shortcodes-slideshow">Slideshow</a>
						</li>
					</ul>

					<h3 id="shortcodes-basics">Shortcode Basics</h3>

					<p>
						When a shortcode is added to post content, it will be displayed in the editor as a code snippet, but when you view the post as a preview or live post, 
						you will see the output of what the shortcode is programmed to do, with the <strong>content</strong> and <strong>attributes</strong> you provide.
					</p>
					<p>
						The shortcodes listed below have a <strong>starting tag</strong> ([my-shortcode]) and an <strong>ending tag</strong> ([/my-shortcode]).  Depending on 
						the shortcode used, such as the [lead] and [blockquote] shortcodes, <strong>content</strong> between the starting and ending tags is used.  Other
						shortcodes, like the [slideshow] shortcode, do not use any content between the starting and ending tags.
					</p>
					<p>
						Some shortcodes use <strong>attributes</strong> to define extra options for whatever the given shortcode does.  For example, the [callout] and [sidebar]
						shortcodes have a "background" attribute, which lets you set a custom background color for the callout box or sidebar.
					</p>

					<p>
						The custom available shortcodes for this site, as well as their available attributes and examples, are listed below.  For information about adding 
						shortcodes to post content, please visit the Adding Content via Shortcodes section of the <a href="#stories">Story documentation</a>.
					</p>

					<hr/>

					<h3 id="shortcodes-blockquote">blockquote</h3>
					<p>
						Creates a stylized blockquote.  Text in the blockquote is large Georgia italic.
					</p>
					<p>
						If a <strong>source</strong> attribute is provided, the blockquote will be styled with oversized starting and ending quotation marks.
					</p>

					<h4>Content</h4>
					<p>
						This shortcode <strong>requires content</strong> between its starting and ending tags.<br/>
						Only a <strong>single line of text</strong> (no line breaks or new paragraphs) is permitted between the shortcode tags.<br/>
						When used in a story, the Default Template Header Font Color is used for the color of the main quote, unless a Text Color attribute
						is specified.
					</p>

					<h4>Attributes</h4>
					<table>
						<tr>
							<th scope="col">Name</th>
							<th scrop="col">Attribute</th>
							<th scope="col">Description</th>
							<th scope="col">Default Value</th>
						</tr>
						<tr>
							<td>Source</td>
							<td>source</td>
							<td>Who said the quote. (optional)</td>
							<td>n/a</td>
						</tr>
						<tr>
							<td>Cite</td>
							<td>cite</td>
							<td>Citing of where the quote came from. (optional)</td>
							<td>n/a</td>
						</tr>
						<tr>
							<td>Text Color</td>
							<td>color</td>
							<td>The color of the blockquote text, source and cite. (optional)</td>
							<td>#000 (source/cite: #444)</td>
						</tr>
					</table>

					<h4>Examples</h4>
					<pre><code>[blockquote]Powerful quote of the day.[/blockquote]</code></pre>
					<pre><code>[blockquote source="Jane Doe" cite="Some Cool Book"]Powerful quote of the day.[/blockquote]</code></pre>

					<hr/>

					<h3 id="shortcodes-callout">callout</h3>
					<p>
						Creates a full-width box that breaks out of the primary content column to help text or other content stand out.
					</p>

					<h4>Content</h4>
					<p>
						This shortcode <strong>requires content</strong> between its starting and ending tags.<br/>
						<strong>Any text, media or other shortcodes</strong> are permitted between the shortcode tags.
					</p>

					<h4>Attributes</h4>
					<table>
						<tr>
							<th scope="col">Name</th>
							<th scrop="col">Attribute</th>
							<th scope="col">Description</th>
							<th scope="col">Default Value</th>
						</tr>
						<tr>
							<td>Background Color</td>
							<td>background</td>
							<td>The color to be used for the background of the callout box.</td>
							<td>#f0f0f0</td>
						</tr>
					</table>

					<h4>Examples</h4>
					<pre><code>[callout background="#e1e1e1"][blockquote]Very very powerful quote![/blockquote][/callout]</code></pre>

					<hr/>

					<h3 id="shortcodes-divider">divider</h3>
					<p>
						Displays a grey divider to break up sections of content.
					</p>

					<h4>Content</h4>
					<p>
						This shortcode <strong>uses no content</strong> between its starting and ending tags.
					</p>

					<h4>Attributes</h4>
					<p>
						This shortcode has no available attributes.
					</p>

					<h4>Examples</h4>
					<pre><code>[divider][/divider]</code></pre>

					<hr/>

					<h3 id="shortcodes-lead">lead</h3>
					<p>
						Creates a slightly-enlarged paragraph with a stylized dropcap.  Intended for use at the beginning of a story or a section of a story.
					</p>
					
					<h4>Content</h4>
					<p>
						This shortcode <strong>requires content</strong> between its starting and ending tags.<br/>
						Only a <strong>single line of text</strong> (no line breaks or new paragraphs) is permitted between the shortcode tags.<br/>
						When used in a story, the Default Template Header Font Color is used for the color of the dropcap.
					</p>

					<h4>Attributes</h4>
					<p>
						This shortcode has no available attributes.
					</p>

					<h4>Examples</h4>
					<pre><code>[lead]This a powerful starting paragraph that needs some attention.[/lead]</code></pre>

					<hr/>

					<h3 id="shortcodes-sidebar">sidebar</h3>
					<p>
						Creates a floating block that other content wraps around.  Used for text or media that is related to a group of text, but doesn't fit within 
						the normal paragraph form of the content.
					</p>

					<h4>Content</h4>
					<p>
						This shortcode <strong>requires content</strong> between its starting and ending tags.<br/>
						<strong>Any text, media or other shortcodes</strong> are permitted between the shortcode tags.
					</p>

					<h4>Atttributes</h4>
					<table>
						<tr>
							<th scope="col">Name</th>
							<th scrop="col">Attribute</th>
							<th scope="col">Description</th>
							<th scope="col">Default Value</th>
						</tr>
						<tr>
							<td>Background Color</td>
							<td>background</td>
							<td>The background color of the sidebar element.</td>
							<td>#f0f0f0</td>
						</tr>
						<tr>
							<td>Position</td>
							<td>position</td>
							<td>Horizontal position of the box (left or right).</td>
							<td>right</td>
						</tr>
					</table>

					<h4>Examples</h4>
					<pre><code>[sidebar background="#e1e1e1" position="left"]This is related content but does not fit inside the main paragraph.[/sidebar]</code></pre>

					<hr/>

					<h3 id="shortcodes-slideshow">slideshow</h3>
					<p>
						Creates a javascript slideshow of a selected Photo Essay.  The Photo Essay will need to be created before placing the slideshow shortcode.  
						Refer to the <a href="#photo-essays">Photo Essay Help section</a> for more information.
					</p>

					<h4>Content</h4>
					<p>
						This shortcode <strong>uses no content</strong> between its starting and ending tags.
					</p>

					<h4>Attributes</h4>
					<table>
						<tr>
							<th scope="col">Name</th>
							<th scrop="col">Attribute</th>
							<th scope="col">Description</th>
							<th scope="col">Default Value</th>
						</tr>
						<tr>
							<td>Photo Essay</td>
							<td>slug</td>
							<td>The slug of the Photo Essay to be used in the slideshow.</td>
							<td>n/a</td>
						</tr>
						<tr>
							<td>Caption Color</td>
							<td>caption_color</td>
							<td>The color of caption text in the slideshow.</td>
							<td>#fff</td>
						</tr>
					</table>

					<h4>Examples</h4>
					<pre><code>[slideshow slug="awesome-photo-essay"][/slideshow]</code></pre>
				</li>

			</ul>
		</div>
	</div>
</div>
