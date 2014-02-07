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
						Enter the title of the story in the title field at the top of the screen.
					</p>

					<h4 id="stories-issue-tags">Assign an Issue, Tags</h4>
					<p>
						On the right-hand side of the screen, select an Issue from the Issues metabox (select the season, i.e. 
						Fall/Spring/Summer, rather than the year) and add any appropriate tags in the Tags metabox.  When picking 
						tags, think about words or phrases that you would use if you were to search for this story on Google.
					</p>

					<h4 id="stories-add-thumbnail">Add a Thumbnail</h4>
					<p>
						The story’s thumbnail should be uploaded as the story’s Featured Image.  To do so, click the “Set featured 
						image” link in the Featured Image metabox on the right side of the screen.  Upload or select an image as 
						usual and click the “Set featured image” button.
					</p>

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

					<h3 id="stories-adding-story-content">Adding Story Content:</h3>
					<p>
						To add content to a story, use the large text editor directly below the title field to type and paste content.  
						Only body copy needs to be added here; the title, story description, and header image are handled separately.
					</p>
					<p>
						Most text is directly editable through the Visual editor.  Make sure that you are in Visual editor mode when 
						you’re adding content—if you don’t see a set of menu buttons above the editor that look like Microsoft Word
						text editor buttons, make sure the “Visual” tab (NOT the “Text” tab) on the top right of the editor is selected.  
						The Visual editor lets you add and manipulate content similarly to how Microsoft Word does.
					</p>

					<h4 id="stories-about-headings">About Headings and Structuring Content</h4>
					<p><strong><em>
						Please read all of the guidelines below—they are very important and help us create content that is semantic, 
						accessible and SEO-friendly!
					</em></strong></p>
					<p>
						On the web, titles and subtitles for any article and its sections are structured in a hierarchical way.  These 
						titles are known as <strong>headings</strong>, and the code behind them allows for up to 6 different tiers of 
						headings—<strong>heading 1 (h1) through heading 6 (h6)</strong>.  View the screenshot below for an example of 
						what these headings are.
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
						<strong>h2's</strong> would be the next most important titles or section headings.  Keep in mind that what we 
						consider a story’s subtitle or description is, generally, <em>NOT</em> an h2.  h2's, as well as all other headings, 
						should always describe the section of content that immediately follows after it.
					</p>
					<p>
						<strong>h3-h6</strong> continue the trend of importance; h6 is the most minor heading.
					</p>

					<p><strong>Notes on Headings:</strong></p>
					<ul>
						<li>
							<strong>Headings should always be assigned in order</strong>—i.e., do not place a h2 under a h3 unless you are 
							defining a new, separate section of content.  Do not skip headings either— i.e., don't jump from a h2 straight 
							to a h4.
						</li>
						<li>
							Do NOT assign text a heading designation if it is not a heading!  <strong>Headings should NOT be 
							assigned to force a particular font size or style on a line of text.</strong>
						</li>
						<li>
							Most of the time, you’ll probably not use h5's or h6's, but they are available if necessary.  You might not use 
							any headings beyond h2 in a story—that is okay, as long as the structure of the story makes sense with the 
							headings you’ve used.
						</li>
					</ul>

					<h4 id="stories-add-headings">Adding Headings</h4>
					<p>
						To add a heading or make an existing line of text a heading, highlight the text you want to modify in the editor, 
						and from the “Paragraph” dropdown in the editor menu, select from the Heading 2 through Heading 6 options.  This 
						text will be styled using the font family and color selected in the Default Template Header Font Family/Color 
						fields.
					</p>

					<h4 id="stories-add-media">Adding Media</h4>
					<p>
						Media (images, videos, and audio) can be uploaded to WordPress’s media library so that they are self-hosted.  
						Generally, this is necessary for photos and video or audio that cannot be uploaded to an external service like 
						Youtube.  <strong>Note:</strong> if you need to add a Youtube, Vimeo, or other hosted video player, you do not 
						need to upload any files; simply paste the URL of the video into the editor content on its own line, and WordPress 
						will figure out the rest for you.
					</p>
					<p>
						Media can be added to the post content by clicking on the Add Media button under the title.
					</p>
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

					<h4 id="stories-add-shortcodes">Adding Content via Shortcodes</h4>
					<p>
						Shortcodes are small snippets of code, wrapped in square brackets [], that do some function or add some 
						predefined content to your story content.
					</p>
					<p>
						On this site, custom shortcodes are available to create blockquotes, callout boxes, sidebars, slideshows, and 
						other stylized content.  Please visit the <a href="#shortcodes">Shortcodes documentation section</a> for 
						information about what shortcodes are available and what options they provide.
					</p>
					<p>
						To add a shortcode to your story content, click the “Add Shortcode” button just under the title field.  In the 
						window that appears, select a shortcode, and update any available options if necessary.  Click the “Insert into 
						Post” button to insert the shortcode where your cursor was last at in the editor.
					</p>

					<h4 id="stories-add-slideshow">Adding a Slideshow</h4>
					<p>
						A slideshow is a rotating set of images with optional captions.  All slideshows use photos from an existing 
						Photo Essay; make sure you’ve created a photo essay with your photos before trying to insert a slideshow shortcode.
					</p>
					<p>
						Slideshows can be added to story content in two ways—the entire story can be a full-screen slideshow, or a small 
						slideshow can be added between other content. Both methods require using the slideshow shortcode; please see the 
						information above on <a href="#stories-add-shortcodes">Adding Content via Shortcodes</a>, and the 
						<a href="#shortcodes">Shortcodes documentation section</a> for more details.
					</p>
					<p>
						<strong>If you’re creating a full-screen slideshow</strong>, the only content in the story editor should be the 
						slideshow shortcode.  After the shortcode is inserted in the story content, make sure to update the Story Template 
						dropdown (in the Story Fields box, under the content editor) to use the Photo essay template.
					</p>

					<h3 id="stories-customization">Story Subtitle, Description, and Customization:</h3>
					<p>
						The Story Fields metabox, located underneath the story content editor, contains set of extra fields that add custom 
						settings to your story.  Most of these fields are straightforward and provide descriptions.
					</p>
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
							<a href="#stories-adding-media">Adding Media documentation section</a> for more information.
						</li>
						<li>
							The Default Template Header Image will be stretched and cropped at different window sizes.  Please keep the 
							composition of the image in mind when designing it—the top and bottom edges of the image will be cropped at some 
							sizes, particularly mobile device size.  Try to center the focus of the composition to the middle of the image.  
							Before publishing your story, upload the Header Image and test what it looks like in Preview mode by resizing your 
							browser window on the front-facing story.
						</li>
					</ul>

					<h3 id="stories-saving">Saving a Story:</h3>
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
						<strong>Photo Essays</strong> are similar to Centerpieces on http://www.ucf.edu. Photo Essays are a means of capturing a set if images
						and captions to be displayed in a Story. Each slide requires a title and image. The caption is optional but
						it is recommended to have one in order to give a frame of reference for the image being displayed. Images added
						to the Photo Essay need to have a consistant height. Also please be aware of the images file size as it will
						increase load time for the user.
					</p>
					<p>
						You can add more slides by clicking on the Add New Slide button which is located under the last slide. Slides are
						displayed in the order they are displayed in the editor (top to bottom). If you need to change the order
						of the slides you can click and drag the Slide header bar up and down then release when you have it in the desired position.
					</p>
					<p>
						The Photo Essays can be displayed using the slideshow Shortcode within the
						Story content. By default the Photo Essays are placed between copy in a given story. If you would like to
						create a Story that is only a Photo Essay then place a single slideshow Shortcode with in the Story content and
						select the Photo Essay Story Template form the Story Fields. This will display the slideshow in fullscreen.
					</p>
				</li>

				<li class="section" id="shortcodes">
					<h2>Shortcodes</h2>

					<p>
						These are widgets or types of code that can be placed within the content of a post, specifically a Story post type. You can access
						these Shortcodes by clicking on the Add Shortcode button underneath title of the Story post. This will
						bring up a screen that will let you select available shortcodes and modify any parameters/settings
						associated with the shortcode (ex. background color). After you are done making your selections you can click
						on insert and the Shortcode will be inserted where your cursor was last placed in the content area. Below is
						a more detailed description of each Shortcode that can be used.
					</p>

					<h3>blockquote</h3>
					<p>Creates a block quote style widget for one off quotes from the story. The quote needs to be placed between the opening and closing shortcode tags.</p>
					<table>
						<tr>
							<th scope="col">Name</th>
							<th scrop="col">Code</th>
							<th scope="col">Description</th>
							<th scope="col">Default Value</th>
						</tr>
						<tr>
							<td>Source</td>
							<td>source</td>
							<td>Who said the quote. (optional)</td>
							<td></td>
						</tr>
						<tr>
							<td>Cite</td>
							<td>cite</td>
							<td>Citing of where the quote came from. (optional)</td>
							<td></td>
						</tr>
						<tr>
							<td>Content</td>
							<td>NA</td>
							<td>What will be displayed as a blockquote. The text is placed between the opening and closing tags.</td>
							<td></td>
						</tr>
					</table>
					<p>Example:
					<pre><code>[blockquote]Powerful quote of the day.[/blockquote]</code></pre>

					<h3>callout</h3>
					<p>This will create a 100% wide box with the given background color. Text, images and shortcodes can be placed as content between the shortcode tags.</p>
					<table>
						<tr>
							<th scope="col">Name</th>
							<th scrop="col">Code</th>
							<th scope="col">Description</th>
							<th scope="col">Default Value</th>
						</tr>
						<tr>
							<td>Background Color</td>
							<td>background</td>
							<td>The color to be used for the background of the callout box.</td>
							<td>#f0f0f0</td>
						</tr>
						<tr>
							<td>Content</td>
							<td>NA</td>
							<td>What will be displayed within the callout. This can be text, video, audio, slideshow, etc. The content is placed between the opening and closing tags.</td>
							<td></td>
						</tr>
					</table>
					<p>Example:
					<pre><code>[callout background="#e1e1e1"][blockquote]Very very powerful quote![/blockquote][/callout]</code></pre>

					<h3>divider</h3>
					<p>Displays a grey divider to breakup the content.</p>
					<p>Example:
					<pre><code>[divider][/divider]</code></pre>

					<h3>lead</h3>
					<p>Displays a paragraph with a large colored starting character and the remaining text is slightly larger than default content text. Generally used for starting paragraphs within a Story.</p>
					<table>
						<tr>
							<th scope="col">Name</th>
							<th scrop="col">Code</th>
							<th scope="col">Description</th>
							<th scope="col">Default Value</th>
						</tr>
						<tr>
							<td>Content</td>
							<td>NA</td>
							<td>Content to be displayed as a lead paragraph. This text will be placed between the opening and closing tags.</td>
							<td></td>
						</tr>
					</table>
					<p>Example:
					<pre><code>[lead]This a powerful starting paragraph that needs some attention.[/lead]</code></pre>

					<h3>sidebar</h3>
					<p>Creates a stand alone element with the given background color. Used for items (ex. text or images) relate to a group of text but does not fit within the normal paragraph form of the content. The main content will wrap around the sidebar.</p>
					<table>
						<tr>
							<th scope="col">Name</th>
							<th scrop="col">Code</th>
							<th scope="col">Description</th>
							<th scope="col">Default Value</th>
						</tr>
						<tr>
							<td>Background Color</td>
							<td>background</td>
							<td>The background color to be used within the </td>
							<td>#f0f0f0</td>
						</tr>
						<tr>
							<td>Position</td>
							<td>position</td>
							<td>Horizontal position the box will be displayed (left or right).</td>
							<td>right</td>
						</tr>
						<tr>
							<td>Content</td>
							<td>NA</td>
							<td>Content to be placed within the sidebar. This will be placed between the opening and closing tags.</td>
							<td></td>
						</tr>
					</table>
					<p>Example:
					<pre><code>[sidebar background="#e1e1e1" position="left"]This is related content but does not fit inside the main paragraph.[/sidebar]</code></pre>

					<h3>slideshow</h3>
					<p>Create a javascript slideshow of a selected Photo Essay. The Photo Essay will need to be created before you place the slideshow shortcode. Refer to the Photo Essay Help section for more information on how you can use the slideshow shortcode.</p>
					<table>
						<tr>
							<th scope="col">Name</th>
							<th scrop="col">Code</th>
							<th scope="col">Description</th>
							<th scope="col">Default Value</th>
						</tr>
						<tr>
							<td>Photo Essay</td>
							<td>slug</td>
							<td>The Photo Essay that you would like to use in the slideshow.</td>
							<td>NA</td>
						</tr>
					</table>
					<p>Example:
					<pre><code>[slideshow slug="awesome-photo-essay"][/slideshow]</code></pre>
				</li>

			</ul>
		</div>
	</div>
</div>
