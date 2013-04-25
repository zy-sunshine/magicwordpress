 <span class="boldtext">1. How do I install BusinessCard onto my wordpress blog? </span>
<div class="indent">
  <p>There are several files included in the ZIP folder. These include wordpress theme files, plugin files, and photoshop files. To installed your wordpress theme you will first need to upload the theme/plugin files via FTP to your server. </p>
  <p>First you are going to upload the theme folder. Inside the ZIP folder you downloaded you will see a folder named &quot;theme.&quot; Within it is a folder named &quot;BusinessCard.&quot; Via ftp, upload the &quot;BusinessCard&quot; folder to your Wordpress themes directory. Depending on where you installed Wordpress on your server, the wp themes folder will be located in a path similar to: /public_html/blog/wp-content/themes. </p>
  <p>Next you need to select BusinessCard and make it your default theme. Click on the design link, and under the themes tab locate BusinessCard from the selection of themes and activate it. Your blog should now be updated with your new theme. </p>
<p>Finally, once the theme has been activated, you should navigate to the Appearances > BusinessCard Theme Options page. Here you can adjust settings pertaining to theme's display. Once you have adjusted whatever settings you would like to change click the "save" button. You must click the "save" button for the options to be saved to the database. Even if you did not change anything you should click the save button once before using the theme to insure that the database has been written correctly.</p>
</div>
<span class="boldtext">2. How do I add icons to my page tabs? </span>
<div class="indent">
  <p>BusinessCard utilizes a script called TimThumb to automatically resize images. Whenever you make a new page you will need to add a custom field. Scroll down below the text editor and click on the &quot;custom fields&quot; link. In the &quot;Name&quot; section, input &quot;Icon&quot; (this is case sensitive). In the &quot;Value&quot; area, input the url to your thumbnail icon image. Your image will automatically be resized and cropped. The default icon size is 32x32px. The image must be hosted on your domain. (this is to protect against bandwidth left) </p>
  <p><span class="style1">Important Note: You <u>must</u> CHMOD the &quot;cache&quot; folder located in the BusinessCard directory to 777 for this script to work. You can CHMOD (change the permissions) of a file using your favorite FTP program. If you are confused try following <a href="http://www.siteground.com/tutorials/ftp/ftp_chmod.htm"><u>this tutorial</u></a><u>.</u> Of course instead of CHMODing the template folder (as in the tutorial) you would CHMOD the &quot;cache&quot; folder found within your theme's directory. </span></p>
</div>
<span class="boldtext">3. How do I add descriptions to my pages? </span>
<div class="indent">The description, which appears after your post title, is added using custom fields. When you make a new page, add a custom field with the name &quot;Tagline&quot; and a value of your tagline text.</div>


  <span class="boldtext">4. How do I create my Portfolio/Gallery section? </span>
  <div class="indent">
  The gallery is created using a WordPress gallery.
  <p>1. The first thing you need to do is customize the size of your gallery thumbnails. To do this click Settings > Media in wp-admin, and change the default thumbnail size to 202x202px</p>
  <p>2. Next you need to upload some images to be used in the gallery. While editing your page, click the &quot;add images&quot; icon, and upload a the collection of images you would like to display. Next click the "gallery" link, and add the WordPres gallery that you would like to display. Make sure you choose "2 columns" and set the &quot;Link thumbnails to:&quot; to &quot;Image file.&quot; Add the gallery to your page. Once added, BusinessCard will automatically convert it into a jQuery slider. </p>
  </div>