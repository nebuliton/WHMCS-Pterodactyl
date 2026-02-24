<!-- NEBULITON PROMO BLOCK -->
<p align="center">
  <a href="https://nebuliton.io" target="_blank">
    <img src="https://nebuliton.io/logo.png" alt="nebuliton.io Logo" width="180" />
  </a>
</p>

<h1 align="center">🚀 Pterodactyl WHMCS Module (Extended) 🚀</h1>

<p align="center">
  <b>The most advanced WHMCS module for <a href="https://github.com/pterodactyl/panel/">Pterodactyl</a> – with live console, power management & dynamic design!</b>
</p>

<p align="center">
  <a href="https://nebuliton.io" target="_blank"><img src="https://img.shields.io/badge/Built%20by-nebuliton.io-6c47ff?style=for-the-badge&logo=cloudsmith" alt="nebuliton.io" /></a>
  <a href="https://discord.gg/5dPWBFbMUS" target="_blank"><img src="https://img.shields.io/discord/1385385189521227826?label=Discord%20Support&logo=discord&style=for-the-badge&color=5865F2" alt="Discord Support" /></a>
</p>

---

<p align="center">
  <a href="https://nebuliton.io" target="_blank"><b>🌐 Powered by nebuliton.io – Your partner for modern hosting solutions!</b></a>
</p>

---

## ✨ Features

- 🖥️ **Live Console:** Real-time terminal access via WebSockets
- ⚡ **Power Actions:** Start, Restart, Stop, and Kill buttons directly in WHMCS
- 📊 **Resource Monitoring:** Dedicated **Resources** tab with live CPU, Memory, Disk and Network charts
- 🎨 **Dynamic Design:** Automatic Light/Dark mode detection (Lagom theme supported)
- 🌍 **Internationalization:** Fully localized in **English**, **German**, **Danish**, **Italian**, **Russian**, **French**, **Dutch**
- 🗂️ **Management Tabs:** Manage Resources, Network (Allocations), Databases, Backups, and Startup variables
- 🛡️ **Security:** Built-in CSRF protection for all AJAX operations
- ⚙️ **Centralized Config:** Addon module for global API settings (editable directly in Addon admin page)
- 🧾 **Usage Metering:** Syncs Disk and Bandwidth usage into WHMCS service usage fields

---

## 📦 Requirements

- 🐦 Pterodactyl Panel **v1.0.0+**
- 🧩 WHMCS **8.x** (recommended) or **7.x**

---

## 🛠️ Installation

1. **Upload Files:**
   - Move the `/pterodactyl/` folder into `<path to whmcs>/modules/servers/`.
2. **Activate Addon:**
   - In WHMCS navigate to **System Settings -> Addon Modules** (WHMCS 8+) or **Setup -> Addon Modules** (WHMCS 7).
   - Activate **Pterodactyl Addon** and configure access permissions.
3. **Configure API Credentials:**
   - Go to **Addons -> Pterodactyl Addon**.
   - Open the **API Settings** section in the addon admin page.
   - Enter your **Pterodactyl URL** (e.g., `https://panel.example.com`).
   - Enter an **Application API Key** (Generated in Admin -> API with all permissions).
   - Enter an **Admin Client API Key** (See "Client API Token" below).
4. **Create Server in WHMCS:**
   - Navigate to **System Settings -> Servers**.
   - Create a new server, choose type **Pterodactyl**.
   - Hostname: Your panel URL (e.g., `panel.example.com`).
   - Password: Your **Application API Key**.
   - Tick **Secure** if using SSL.
5. **Create Server Group:**
   - Create a group and add the Pterodactyl server to it.
6. **Product Configuration:**
   - Navigate to **System Settings -> Products/Services**.
   - Create a new product (Type: Other).
   - In **Module Settings**, choose **Pterodactyl** and select your server group.
   - Fill in the required fields (Nest ID, Egg ID, etc.).

---

## 🔌 WebSocket & Console Setup

### 1️⃣ Configure Allowed Origins (Wings)
The browser needs permission to connect to the Wings WebSocket from your WHMCS domain.
- Go to your **Pterodactyl Panel -> Settings -> General**.
- Find the **Allowed Origins** field.
- Add your WHMCS URL (e.g., `https://whmcs.yourdomain.com`).
- Save the settings. Wings will now accept WebSocket connections from your WHMCS site.

### 2️⃣ Create Client API Token
The module uses a Client API token to fetch WebSocket authentication details.
- Log into your Pterodactyl Panel as an **Administrator**.
- Go to **Account -> API** (Top right user menu).
- Create a new API Key.
- Copy this key and paste it into the **Pterodactyl Addon settings** in WHMCS (Field: Admin Client API Key) or into the server's **Access Hash** field.

---

## 📈 Usage Metering & Resource Graphs

- The client area includes a **Resources** tab with live usage cards and history charts.
- The module implements WHMCS **UsageUpdate** to keep Disk/Bandwidth usage in sync with Pterodactyl resource data.

---

## ⚙️ Overwriting values through configurable options

Overwriting values can be done through either Configurable Options or Custom Fields.
- You can set the API Keys also via code in the `pterodactyl.php` file.
- Their name should be exactly what you want to overwrite (e.g., `dedicated_ip`).

**Valid options:**
`server_name, memory, swap, io, cpu, disk, nest_id, egg_id, pack_id, location_id, dedicated_ip, port_range, image, startup, databases, allocations, backups, oom_disabled, username`

**Environment Variables:**
You can also overwrite environment variables. Use the variable name (e.g., `PLAYER_SLOTS`) or the friendly name.
Example: `PLAYER_SLOTS|Player Slots`

---

## ❓ FAQ

<details>
<summary>❌ <b>Couldn't find any nodes satisfying the request</b></summary>
This can be caused by: Wrong location ID, not enough disk space/CPU/RAM on nodes, or no allocations matching the provided criteria.
</details>

<details>
<summary>👤 <b>The server gets assigned to the first/admin user</b></summary>
Ensure you are using the latest version of this module and that the `external_id` is correctly passed to Pterodactyl.
</details>

<details>
<summary>🎨 <b>Changing the default colors</b></summary>
You can change from the default whitemode to darkmode in the `clientarea.tpl` file under "pteroDefaultTheme". It detects the Lagom dark/white mode automatically.
</details>

<details>
<summary>🐞 <b>How to enable module debug log</b></summary>
1. Go to **System Logs -> Module Log**.
2. Click **Enable Debug Logging**.
3. Perform the action that failed.
4. Review the logs for "Pterodactyl-WHMCS" entries.
</details>

---

## 👥 Support & Community

- 💬 **Discord Support:** [Join now!](https://nebuliton.io/go/discord)
- 🌐 **Learn more:** [nebuliton.io](https://nebuliton.io)

---

## 🙏 Credits

- Based on the original work by [death-droid](https://github.com/death-droid) and [Stepas](https://github.com/Stepas)
- Enhanced and modernized for Pterodactyl 1.x and Lagom theme integration
- Developed/Upgraded by the [nebuliton.io](https://nebuliton.io) Team

---

<p align="center">
  <b>🚀 Ready for the future of hosting? <a href="https://nebuliton.io" target="_blank">Try nebuliton.io now!</a> 🚀</b>
</p>
