<div id="theme-help" class="i-am-a-fancy-admin">
	<div class="container">
		<h2>Help</h2>

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
					<h3>Intro</h3>
					<p>
						The goal of the help section is to familiarize yourself with the Pegasus website and the
						different types of content that can be created. This should also help you understand the flow
						of content creation for Pegasus.
					</p>
					<p>
						The main types of content that can be created are <strong>Issues</strong>, <strong>Stories</strong>, <strong>Photoessays</strong> and
						<strong>Shortcodes</strong>. <strong>Issues</strong> are a means of categorizing a group of Stories much like the
						physical magazine. <strong>Stories</strong> are where the meat of the content will reside. The <strong>Stories</strong> that will
						live on the website are hand picked from the physical magazine. The <strong>Photo Essay</strong> is a type of content that is
						used to display a series of images with captions in a Story using a slideshow format. <strong>Shortcodes</strong> are a set of
						tools that can be used to add special content like dividers, blockquotes and sidebars to a <strong>Story</strong>.
					</p>
				</li>

				<li class="section" id="issues">
					<h3>Issues</h3>

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
					<h3>Stories</h3>

					<p>
						<strong>Stories</strong> are a type of post. They are similar to standard posts, except
						they are customized to display story specific information. The title should be the title given
						to the printed version of the Story. The content area is where the copy will reside. Story specific
						enhancements can be placed within the content area like Media and Shortcodes.
					</p>
					<p>
						Media can be added to the content by clicking on the Add Media button under the title. You can either
						browse or drag for the Media you desire to be apart of the story. After the Media has been uploaded
						you will need to give the media a title. For an image media type you will need to add caption (optional) and alt text.
						Once you have filled out all the information then click Insert into post.
						The media will then be placed where your cursor was last at in the content area.
					</p>
					<p>
						Shortcodes can be used to enhance the content. You can access the
						Shortcode selector by clicking on the Add Shortcode button just under the title field. Once you
						have made your Shortcode selection you can insert it. The Shortcode will be placed where your cursor
						was last at in the content area. For more information on the different Shortcodes please visit the Shortcodes
						section within Help.
					</p>
					<p>
						The Story will need to be added to the Issue by selecting the year and term within the Issues metabox to the
						right of the content area. Additional settings can be made for inidividual Stories that alter the way the Story is styled. These settings
						are found under content area labeled Story Fields. Below explains the different pieces within the Story Fields section.
					</p>
					<p>
						<ul>
							<li><strong>Story Template:</strong> This dropdown will allow you to change the template being used. The Default template is a big image header with content below (see Header Image below). The Photo Essay template should only be used when you place a single Slideshow shortcode into the content area. Custom Story template is only used to develop custom content for the Story.</li>
							<li><strong>Story Subtitle:</strong> Adding a subtitle text will add text under the Story thumbnail in the Story listing (Story Listing is located at the bottom of each page).</li>
							<li><strong>Story Description:</strong> Adding description will add text right under the Story title much like a sub heading.</li>
							<li><strong>Header Font Family:</strong> This will set the font family to use for all the headers (h1-h6) on the Story.</li>
							<li><strong>Header Font Color:</strong> Sets a color to all the headers (h1-h6) on the Story.</li>
							<li><strong>Header Image:</strong> This will set the header image used in the Default style Story Template. When creating these images please crop them to be 1600x900 (16:9 aspect ratio).</li>
						</ul>
					</p>
				</li>

				<li class="section" id="photo-essays">
					<h3>Photo Essays</h3>

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
					<h3>Shortcodes</h3>

					<p>
						These are widgets or types of code that can be placed within the content of a post, specifically a Story post type. You can access
						these Shortcodes by clicking on the Add Shortcode button underneath title of the Story post. This will
						bring up a screen that will let you select available shortcodes and modify any parameters/settings
						associated with the shortcode (ex. background color). After you are done making your selections you can click
						on insert and the Shortcode will be inserted where your cursor was last placed in the content area. Below is
						a more detailed description of each Shortcode that can be used.
					</p>

					<h4>blockquote</h4>
					<p>Creates a block quote style widget for one off quotes from the story. The quote needs to be placed between the opening and closing shortcode tags.</p>
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
							<td>What will be displayed as a blockquote. The text is placed between the opening and closing tags.</td>
							<td></td>
						</tr>
					</table>
					<p>Example:
					<pre><code>[blockquote]Powerful quote of the day.[/blockquote]</code></pre>

					<h4>callout</h4>
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

					<h4>divider</h4>
					<p>Displays a grey divider to breakup the content.</p>
					<p>Example:
					<pre><code>[divider][/divider]</code></pre>

					<h4>lead</h4>
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

					<h4>sidebar</h4>
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

					<h4>slideshow</h4>
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
