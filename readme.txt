=== Free Support Chat Widget by Brane ===
Contributors: branelabs
Tags: live chat, support chat, chat widget, customer support, contact form
Requires at least: 5.8
Tested up to: 7.1
Requires PHP: 7.4
Stable tag: 1.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

A free live chat widget for your site. Visitors message you from any page and you reply from the Brane app on your phone.

== Description ==

Free Support Chat Widget by Brane puts a real live chat on your website in about two minutes. There is no monthly fee, no seat limit and no chatbot. Visitors message you from any page, and you answer from the free Brane app on your phone, with a notification the moment a message arrives.

= Why site owners use it =

* **Completely free.** No plan to upgrade to, no per agent pricing, no message cap.
* **Answer from your phone.** Every conversation lands in the Brane app on iPhone or Android, so you can reply from anywhere.
* **Instant notifications.** You hear about a new message straight away, not when you next log in to a dashboard.
* **Real people, not a bot.** The chat tells visitors they are talking to a person, which is what most people want to hear.
* **Better than a contact form.** A form is a message into the dark. A chat is a conversation the visitor can come back to, and you can reply days later.
* **Visitors hear your reply.** If they have closed the tab, your reply is emailed to them, and the conversation is waiting where it stopped.
* **Fast sign up for visitors.** An email address and a date of birth, then they are talking to you. No app download, no phone number.
* **Lightweight.** The loader is about 5KB and loads nothing else until somebody opens the chat.
* **Works on phones.** On small screens the chat opens full screen.

= Make it look like your brand =

* Brand colour, with a colour picker or any hex code
* Unread badge colour
* Light or dark theme
* Bottom left or bottom right
* Your own welcome message
* A floating chat button, or your own button, menu link or shortcode
* Or embed the chat right inside a page, full width, with `[brane_chat_embed]`
* A live preview on the settings page as you change things

= Set up in three steps =

1. Install the free Brane app from the App Store or Google Play and allow notifications.
2. Create a username with your brand, for example yourbrandsupport.
3. Paste the username into **Brane > Support Chat**, pick your colours and switch the chat on.

= Embed the chat in a page =

Paste `[brane_chat_embed]` into your Contact or Support page and the chat appears in the page, full width. It is 640 pixels tall by default, or set a height with `[brane_chat_embed height="800"]` or `[brane_chat_embed height="80vh"]`. It shows wherever you paste it, even with the floating chat switched off, and the floating button is left off on that page.

= Open the chat from your own button =

Any of these opens the chat:

* The shortcode `[brane_chat_button text="Chat with us"]`
* A menu link or button whose URL is `#brane-chat`
* Any element with the CSS class `brane-open-chat`

== External services ==

This plugin connects your site to Brane, a messaging service run by Brane Labs. It is what shows the chat and delivers messages to you.

When the chat is switched on, every page of your site loads the chat loader from `https://embed.brane.chat/embed.js`. The loader receives the settings you chose on the settings page (your Brane username, colours, theme, position and welcome text). Nothing else is loaded until a visitor opens the chat.

When a visitor opens the chat, the chat panel loads from `embed.brane.chat`. If they start a conversation, the visitor gives an email address and a date of birth to create a Brane account, and the messages they send are stored by Brane and delivered to your Brane account. Your reply may be emailed to the visitor if they are not on your site when it arrives.

* Terms of service and privacy policy: https://brane.app/legal/

The page embed (`[brane_chat_embed]`) loads the chat panel from `embed.brane.chat` in an iframe on the pages where you paste it, with the same settings and the address of that page.

No data is sent to Brane while the floating chat is switched off and no page carries the page embed.

== Installation ==

= From your WordPress dashboard =

1. Download `free-support-chat-widget-by-brane.zip` from the latest release at https://github.com/brane-labs/free-support-chat-widget-wordpress/releases
2. Go to **Plugins > Add New Plugin > Upload Plugin**.
3. Choose the zip, click **Install Now**, then **Activate**.
4. Open **Brane > Support Chat** in the dashboard menu and follow the steps.

= By FTP =

1. Unzip the file.
2. Upload the `free-support-chat-widget-by-brane` folder to `/wp-content/plugins/`.
3. Activate the plugin on the **Plugins** screen.

== Frequently Asked Questions ==

= Is it really free? =

Yes. The plugin and the Brane app are free, with no limit on conversations.

= Do my visitors need to download an app? =

No. Visitors chat right on your page. Only you need the Brane app, to reply.

= Is it a chatbot? =

No. Every message reaches you, a real person, and the chat says so.

= How do I get notified about new messages? =

Allow notifications when the Brane app asks. You then get a notification on your phone for every new message.

= Can I put the chat inside a page instead? =

Yes. Paste `[brane_chat_embed]` into any page and the chat appears there, full width.

= Can I hide the floating button? =

Yes. Choose **My own button only** and use the shortcode, a `#brane-chat` link or the `brane-open-chat` class to open the chat.

= Can I change the colours? =

Yes. Pick any brand colour and badge colour with the colour picker or a hex code, and choose a light or dark theme.

= Which side of the screen does it appear on? =

Bottom right by default. You can switch it to bottom left.

= Does it slow my site down? =

The loader is about 5KB and nothing else loads until a visitor opens the chat.

= Does it work with page builders and caching plugins? =

It adds one script to the footer of every page, which works with any theme or builder. If a plugin that delays or combines JavaScript stops the chat appearing, exclude `embed.brane.chat` from it.

== Screenshots ==

1. The Support Chat settings page with the live preview.
2. The chat on a website.

== Changelog ==

= 1.0.0 =
* First release.

== Upgrade Notice ==

= 1.0.0 =
First release.
