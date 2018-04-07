# PHP Tinder Auto Liker
It's working with fresh (april 2018) Tinder API! 
PHP script is getting user profiles depends on your settings in the app and liking them all.
Results can be stored in the .csv table.

# How to use
First of all you'll need to get the Facebook's app id and tokken.
I use Charles Proxy applicaion to sniff them from my phone.
1) Install the Charles Proxy on your PC.
2) Press "Help - SSL Proxying - Install Charles Root Certificate on a Mobile Device".
3) Follow instrutions to install the mobile certificate.
4) Try to auth in Tinder and catch the /auth request in the Charles Proxy.
5) Insert id and token from the request's headers into config.ini