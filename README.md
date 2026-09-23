# GP Healthcare Hospital — WordPress Theme

A complete eye-hospital theme: CMS-driven home page, doctors, specialities, blog, online appointment booking, patient dashboard, doctor portal with digital prescriptions, contact form and a full admin settings panel. Everything is managed from wp-admin.

---

## 1. Install

1. Zip this folder (or upload it as-is) and install it via **Appearance → Themes → Add New → Upload Theme**.
2. Click **Activate**.

On activation the theme automatically:

- creates the pages **Home, About Us, Contact, Book Appointment, Patient Login, My Dashboard, Doctor Portal, Blog** and assigns the right page templates,
- sets Home as the static front page and Blog as the posts page,
- creates a **Primary Menu** and assigns it,
- adds the **Patient** and **Doctor** user roles,
- seeds 8 specialities, 4 doctors and 3 testimonials as demo content so nothing looks empty.

3. Go to **Settings → Permalinks** and click **Save** once. This is required for `/doctors/`, `/specialities/` and the secure prescription links to work.

---

## 2. Who logs in where

| Who | URL | Notes |
|---|---|---|
| **Admin** | `/wp-admin` | Normal WordPress administrator account. |
| **Patient** | `/patient-login/` | Registers themselves on the same page. Role: *Patient*. |
| **Doctor** | `/patient-login/` | Same form. You create the user; role: *Doctor*. |

Patients and doctors are kept out of wp-admin automatically and land on their own dashboard after login.

### Giving a doctor portal access

1. **Users → Add New** — create a user, set **Role = Doctor**. They get a welcome email.
2. **Doctors → edit the doctor** — set **Linked WordPress user** to that user, and fill **Notification email**.

That doctor now sees only their own appointments and can issue prescriptions.

---

## 3. Admin menus

| Menu | What it does |
|---|---|
| **Hospital Settings** | Everything on the public site: contact details, social links, WhatsApp, map, home-page sections, **colours**, footer, appointment rules, advertisement popup. |
| **Doctors** | Doctor profiles: speciality, qualification, experience, languages, fee, available days, on-duty toggle, portal user link. |
| **Specialities** | Treatment pages: hero text, techniques list, images, doctor keyword. |
| **Testimonials** | Patient stories shown in the home slider. |
| **Appointments** | Every booking, filterable by status; edit or change status here. |
| **Prescriptions** | Read-only record of everything doctors have issued, with the secure link. |
| **Posts** | Normal WordPress blog. |

The WP dashboard also gets a **Hospital overview** widget: today's appointments, pending confirmations, reschedule requests, doctor/patient/prescription counts, plus the next six appointments.

---

## 4. Home page sections

**Hospital Settings → Home Page → Sections (order & visibility)** is a comma-separated list:

```
hero,ayushman,why,about,stats,services,doctors,testimonials,blogs,faq
```

- Remove a name to hide that section.
- Reorder the names to move sections around.

| Name | Content source |
|---|---|
| `hero` | Hospital Settings → Home Page. Includes a **live booking form** (doctor + date + real-time slots) and the stats strip, so patients can book without leaving the hero. |
| `ayushman` | Hospital Settings (one card per line: `Title\|Description\|Image URL`) |
| `why` | Hospital Settings (same `Title\|Description\|Image URL` format) |
| `about` | Headline/text from settings; the four cards are the first four **Specialities** |
| `stats` | Hospital Settings (`Number\|Label` per line) — numbers count up when scrolled into view |
| `services` | **Specialities** post type |
| `doctors` | **Doctors** post type |
| `testimonials` | **Testimonials** post type |
| `blogs` | Latest 3 **Posts** |
| `faq` | Hospital Settings (`Question\|Answer` per line) |

In the hero title, wrap a word in `{curly braces}` to colour it, e.g. `Clear vision, {compassionate} care`.

The hero image is used as a dark backdrop behind the text, so pick a wide photo — it is dimmed automatically and never needs to be light.

The hero booking form follows the same rules as the popup: doctor availability, already-taken slots, duplicate-booking checks and the login requirement (Hospital Settings → Appointments).

---

## 5. Appointments

**Hospital Settings → Appointments** controls:

- **Time slots** — comma separated, e.g. `09:00 AM, 10:00 AM, 11:00 AM, 02:00 PM, 04:00 PM`
- **Default consultation fee** (a doctor's own fee overrides it)
- **Auto-confirm new bookings** — off means bookings start as *Pending*
- **Patients must log in to book**
- **Notification email** — also receives contact-form messages

The booking wizard (3 steps: details → doctor → date & time) automatically:

- hides days the doctor is not available,
- hides slots already booked and slots already past today,
- blocks a second active booking by the same patient with the same doctor on the same day,
- emails the patient, the doctor and the notification address.

Statuses: Pending, Confirmed, Completed, Cancelled, Reschedule requested. Patients can cancel or request a reschedule from their dashboard; doctors can confirm, cancel or prescribe.

---

## 6. Prescriptions

A doctor opens **Prescribe** on an appointment, enters a diagnosis, one or more medications (name, dosage, duration, instructions) and follow-up notes.

On save the theme:

- stores it under **Prescriptions**,
- marks the appointment **Completed**,
- emails the patient a secure link: `/prescription/{random-32-char-token}/`

That page needs no login (the token is the key), is `noindex`, and is print-styled with the hospital letterhead. Patients also see all of their prescriptions in their dashboard.

---

## 7. Contact form & WhatsApp

The contact page form validates name, email, phone and message, has a hidden honeypot field for bots, and emails the address in **Hospital Settings → Appointments → Notification email** with the sender as Reply-To.

Set **WhatsApp number** (with country code, digits only, e.g. `919525334214`) in Hospital Settings to show the floating WhatsApp button and the WhatsApp card on the contact page.

---

## 8. Colours

**Hospital Settings → Colors** recolours the whole site with no code:

- **Brand colour** — buttons, links, icons, highlights
- **Dark colour** — headings, footer, hero and other dark sections

Six ready-made palettes (Teal, Blue, Green, Indigo, Maroon, Orange) fill both fields in one click. From each colour the theme generates the full 50–950 shade range, so hovers, tints and borders stay consistent.

Clear both fields to return to the default. Pick a medium or dark brand colour — white button text becomes hard to read on very light ones.

---

## 9. Editing the design

Styles are Tailwind, compiled to `assets/css/theme.css`. To change them:

```bash
npm install
npm run dev      # watch while editing
npm run build    # minified production build
```

Edit `assets/src/theme.css` (design tokens and component classes) and `tailwind.config.js` (colours, shadows, fonts). **Do not edit `assets/css/theme.css` by hand** — it is generated.

If you never touch the design, you do not need Node at all; the compiled CSS ships with the theme.

Colour palette: `primary` (medical teal) and `navy` (headings, footer, dark sections).

---

## 10. File map

```
functions.php              loads everything in inc/
inc/
  helpers.php              bs_opt(), icons, section headers, formatting
  setup.php                theme supports, menus, asset loading
  roles.php                Patient + Doctor roles, wp-admin lockout
  post-types.php           doctors, specialities, testimonials, appointments, prescriptions
  meta-boxes.php           admin fields + list columns for all of the above
  settings.php             Hospital Settings page (tabbed)
  colors.php               admin colour palette -> CSS variables
  appointments.php         slot logic, booking rules, queries
  prescriptions.php        prescription creation + secure token lookup
  auth.php                 front-end login/registration
  ajax.php                 all AJAX endpoints (nonce-protected)
  emails.php               HTML emails
  admin-dashboard.php      wp-admin overview widget
  activation.php           pages, menu, demo content on activation
templates/                 page templates + prescription view
template-parts/
  sections/                one file per home-page section
  dashboard/               appointment row shared by both dashboards
assets/
  src/theme.css            Tailwind source (edit this)
  css/theme.css            compiled output (generated)
  js/theme.js              booking wizard, forms, tabs, FAQ, slider
  js/admin.js              media picker for image fields
```

---

## 11. Emails not arriving?

WordPress uses PHP `mail()` by default, which most shared hosts block or send straight to spam. Install an SMTP plugin (WP Mail SMTP, FluentSMTP, …) and point it at your mailbox. The theme's emails then go out through it with no further changes.

---

## 12. Renaming the theme for another brand

Everything is namespaced consistently, so a rebrand is a find-and-replace:

| What | Current value |
|---|---|
| Theme folder | `bs-healthcare-theme` |
| Text domain | `bshealthcare` |
| PHP function prefix | `bs_` |
| PHP constants | `BS_DIR`, `BS_URI`, `BS_VERSION` |
| Post meta keys | `_bs_*` |
| Post types | `bs_doctor`, `bs_service`, `bs_testimonial`, `bs_appointment`, `bs_prescription` |
| Options | `bs_settings`, `bs_installed` |
| Roles | `bs_patient`, `bs_doctor` |
| CSS classes / DOM ids | `bs-*` |
| JS global | `BSHC` |
| Settings page slug | `bs-settings` |
| Booking reference prefix | `BSH-` |

Note: changing the post-type or meta-key prefixes on a **live site** orphans existing content, because the data is stored under the old names. Rename before launch, or migrate the database afterwards.
