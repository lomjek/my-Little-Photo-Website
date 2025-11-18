# my Little Photo Website
Welcome to my little Project. You can find this Webpage through all it's versions in this github repo. I am not very happy with every one of them, but hey! You can always improve.

### Why this project?
My cousin and I live appart and we wanted to have a way to exchange the photos we made, but also sharing it with the world. I have learned very much on my journey and have much to learn on the way ahead.

### Why Apache2/php?
I know that there are shiny Frameworks and stuff, but I wanted to go with something established and well known. That is why I chose this setup. It is not a LAMP Server, more like LAPP (Linux Apache Python Php) Server.

## If you host this yourself, you have to verify following things:
- Is _rewrite_mod_ for Apache2 enabled and active?
- Is the hosting folder allowed to use _.htaccess_?
- Are the correct owners and permissions set? (php has to be allowed to write into the folder.)
- Do you have python3 and PIL or pillow installed?
- Is your php configuration right? (Filesize, POST_MAX_SIZE)
- Add this to the configuration file: __DirectoryIndex index.html index.php main.html main.php__
- (Is the Firewall and Port Forwarding active?)

### This project is licensed under the MIT License.
