# Free Support Chat Widget for WordPress

**A free live chat plugin for WordPress websites. Visitors message you from any page, and you reply from the Brane app on your phone.** No monthly fee, no seat limits, no chatbot. Just a real support chat for your business, shop, club or blog.

Free Support Chat Widget by Brane is a lightweight WordPress live chat plugin made by [Brane Labs](https://brane.app/business/). It adds a customer support chat button to every page of your site, with your brand colour, a light or dark theme and the corner of your choice. Every message lands in the free Brane app on iPhone and Android, with a push notification the moment it arrives, so you can answer customers from anywhere.

- **Download:** [latest release zip](https://github.com/brane-labs/free-support-chat-widget-wordpress/releases/latest)
- **Plugin page:** [brane.app/business/wordpress](https://brane.app/business/wordpress/)
- **WordPress.org plugin directory:** coming soon
- **Get the Brane app:** [App Store](https://apps.apple.com/app/brane-find-your-people/id1525970435) · [Google Play](https://play.google.com/store/apps/details?id=app.brane.messenger)

## Features

- **100% free live chat.** No paid plan, no per agent pricing and no cap on conversations.
- **Reply from your phone.** Answer website visitors from the Brane app on iOS or Android.
- **Instant notifications.** Get a notification for every new chat message, not when you next open a dashboard.
- **Real people, not a chatbot.** The chat tells visitors they are talking to a person.
- **Replaces your contact form.** A chat is a conversation visitors can come back to, and you can reply days later. If they have left your site, your reply is emailed to them.
- **No app needed for visitors.** They chat right on your page after giving an email address and a date of birth.
- **Brand colours.** Pick a colour with the visual colour picker or type any hex code.
- **Light or dark theme.**
- **Bottom left or bottom right.**
- **Custom welcome message.**
- **Live preview** on the settings page as you change things.
- **Embed the chat in a page.** Paste `[brane_chat_embed]` into your Contact or Support page and the chat sits right in the page, full width.
- **Use your own button.** Hide the floating chat bubble and open the chat from a menu link, a button or a shortcode.
- **One on / off switch.** Turn the chat on or off for the whole site in one click.
- **Lightweight and fast.** The loader is about 5KB and loads nothing else until somebody opens the chat.
- **Mobile friendly.** The chat opens full screen on phones.
- **Works with any theme** and page builder, including Elementor, Divi, Beaver Builder and the block editor.

## How to install the plugin

### 1. Download the zip

Go to the [latest release](https://github.com/brane-labs/free-support-chat-widget-wordpress/releases/latest) and download **`free-support-chat-widget-by-brane.zip`** under **Assets**.

> Use the release zip rather than GitHub's green **Code > Download ZIP** button. The release zip has the right folder name, so future updates install over it cleanly.

### 2. Upload it to WordPress

1. Log in to your WordPress dashboard.
2. Go to **Plugins > Add New Plugin**.
3. Click **Upload Plugin** at the top of the page.
4. Choose `free-support-chat-widget-by-brane.zip` and click **Install Now**.
5. Click **Activate Plugin**.

You will now see a **Brane** menu in the WordPress sidebar.

### 3. Set up your support chat

Open **Brane > Support Chat** and follow the steps on the page:

1. **Get the Brane app** from the [App Store](https://apps.apple.com/app/brane-find-your-people/id1525970435) or [Google Play](https://play.google.com/store/apps/details?id=app.brane.messenger), and allow notifications so you hear about every message straight away.
2. **Create a username with your brand**, for example `yourbrand` or `yourbrandsupport` (letters and numbers only), and add your logo as the profile photo.
3. **Paste your username** into the plugin.
4. **Make it yours.** Choose your brand colour, light or dark, left or right, and a welcome message.
5. **Switch it on** and click **Save changes**.

That is it. Visit your site and the support chat is there.

### Installing by FTP

Unzip the file and upload the `free-support-chat-widget-by-brane` folder to `/wp-content/plugins/`, then activate it on the **Plugins** screen.

## Embed the chat in a page

Want the chat inside your Contact or Support page instead of, or as well as, a floating button? Paste this shortcode into the page, in a Shortcode block or any text block:

```
[brane_chat_embed]
```

The chat appears full width, using your username, colours and theme. It is 640 pixels tall by default. Set your own height in pixels or as a share of the screen:

```
[brane_chat_embed height="800"]
[brane_chat_embed height="80vh"]
```

The page embed shows wherever you paste it, even with the floating chat switched off. On a page that has it, the floating button is left off so the chat is not there twice.

## Open the chat from your own button

Choose **My own button only** to hide the floating chat bubble, then use any of these:

| Method | Example |
| --- | --- |
| Shortcode | `[brane_chat_button text="Chat with us"]` |
| Menu link | Add a Custom Link to your menu with the URL `#brane-chat` |
| CSS class | Add the class `brane-open-chat` to any button or block |

These also work with the floating button switched on.

## Settings

| Setting | Options |
| --- | --- |
| Support chat | Floating chat on or off |
| Username | Your Brane username |
| Theme | Light or dark (visitors can switch) |
| Position | Bottom left or bottom right |
| Brand colour | Any colour or hex code |
| Unread badge colour | Any colour or hex code |
| Welcome message | Text shown before a visitor starts |
| Chat button | Floating button, or your own button only |
| Button label | Read out by screen readers |
| Visitor sign up | Optionally ask for a photo, a name and bio, or interests |

## Frequently asked questions

### Is this live chat plugin really free?

Yes. The plugin and the Brane app are free, with no limit on chats.

### Do website visitors need to install anything?

No. Visitors chat on your website. Only you need the Brane app.

### Is it a chatbot or AI chat?

No. It is live chat with a real person: you.

### How do I get notified of new chat messages?

Allow notifications in the Brane app. Each new message sends a notification to your phone.

### Will it slow down my WordPress site?

No. The script is about 5KB and the chat itself only loads when a visitor opens it.

### Does it work with caching and optimisation plugins?

Yes. If a plugin that delays or combines JavaScript stops the chat appearing, exclude `embed.brane.chat` from it.

### What data is shared?

While the floating chat is on, your pages load the chat from `embed.brane.chat` with your username and design settings. Visitors who start a chat give an email address and date of birth, and their messages are delivered to your Brane account. See the [Brane terms and privacy policy](https://brane.app/legal/). Pages carrying the page embed load the chat panel from the same place. Nothing is sent while the floating chat is off and no page carries the page embed.

## Requirements

- WordPress 5.8 or later
- PHP 7.4 or later
- A free Brane account

## Building the zip yourself

```bash
./bin/build-zip.sh
```

The installable zip is written to `dist/free-support-chat-widget-by-brane.zip`. Publishing a GitHub release builds the same zip and attaches it automatically.

## License

GPLv2 or later. Made by [Brane Labs](https://brane.app/business/).
