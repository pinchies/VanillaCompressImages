# VanillaCompressImages
Simple php scripts for Vanilla Forum, to autocompress JPEG images in your uploads folder.
1. Upload both scripts to your public_html folder.
2. Configure the $domain variable for your domain name in the compressimages file.
3. When you run the <$yourdomainname>/compressimages.php script, images will be automatically compressed and the originals DELETED, one image with every run, to avoid server slowdown.
4. If you want to optimise the images one by one manually, then visit <$yourdomainname>/compressimages.php?manual=1
5. Setup a cron job to run the php script as frequently as you would like.
6. If this helps you feel free to [tip me](https://www.paypal.me/samjp/4). :-)



