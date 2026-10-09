# Home Visit GO! (家訪 GO!)

A responsive web application (RWD) designed to streamline medical home visit logistics, schedule tracking, and route planning for healthcare providers[cite: 1, 2].

---

## Overview

**Home Visit GO!** is a responsive web application developed to help physicians and medical administrators manage home visit workflows efficiently[cite: 1, 2]. The platform provides role-based visit list tracking, flexible patient query tools, and integrated Google Maps navigation featuring multi-stop route optimization and transit cost/distance estimations[cite: 1, 3, 6, 8, 9].

---

## Key Features

- **Role-Based Access Control (RBAC):** Separates permissions between attending physicians (who view only their assigned patients) and administrative managers (who have comprehensive access to all visit logs)[cite: 1].
- **Multi-Factor Patient Search:** Allows quick filtering of home visit records using patient name, national ID, medical record number (MRN), or visit date[cite: 3].
- **Single-Location Map Navigation:** Direct link to an interactive Google Maps pin and address popup for individual visits[cite: 5, 6].
- **Multi-Stop Route Planning & Cost Estimation:** Displays aggregated routes across all daily visit targets, detailing segment-by-segment taxi fare estimates, distances, and walking recommendations[cite: 6, 9].
- **Anonymous Post-Session Feedback:** Automatically routes users to an anonymous satisfaction survey upon logging out to gather UI/UX feedback[cite: 10, 11, 12].

---

## System Requirements & Environment

- **Current Beta / Testing Stage:** The system currently requires deployment on a host machine with database access or a device connected to the same local subnet (LAN)[cite: 1].
- **Production Roadmap:** Accessible via any standard web browser on internet-connected devices (laptops, tablets, mobile phones) once fully deployed[cite: 1].

---

## System Operation & User Workflow

### 1. Authentication & Access Control

1. **Accessing the Portal:** Open a browser and navigate to the application URL (e.g., `http://<server-ip>/RWD/index.php`)[cite: 2].
2. **Logging In:** Enter your **Account (帳號)** and **Password (密碼)**, then click **Login (登入)**[cite: 2].
   - **Form Validation:** If required fields are left blank, a prompt will notify the user before submission[cite: 2].
   - **Authentication Errors:** An alert modal dialog will notify the user if credentials are invalid; click **OK (確定)** to retry[cite: 3].
3. **Role Permissions:**
   - **Attending Physicians:** Can view and manage their own assigned home visit schedules[cite: 1].
   - **Administrators:** Can inspect, monitor, and query all visits across all doctors[cite: 1].

---

### 2. Record Search & Filtering

After authentication, the system lands on the **Search Screen (分類查詢頁面)**[cite: 3, 4]:

- **Search Criteria:** Filter records by one or multiple fields[cite: 3]:
  - **Patient Name (姓名)**[cite: 3, 4]
  - **National ID (身分證字號)**[cite: 3, 4]
  - **Medical Record Number (病歷號碼)**[cite: 3, 4]
  - **Visit Date (家訪日期)**[cite: 3, 4]
- **Action Buttons:**
  - **Search (查詢):** Submits criteria to fetch matching visit entries[cite: 4].
  - **Clear (清除):** Resets all dropdowns and input filters[cite: 4].

---

### 3. Visit Records & Google Maps Navigation

Results display in a structured list detailing: **Date**, **Patient Name**, **Appointment Time**, **Time Period (Morning/Afternoon)**, and **Address**[cite: 5].

#### A. Single-Address Navigation
- Click the **Pin/Map Icon** next to any specific address in the results list to view the pinned location on Google Maps[cite: 5, 6].
- The address info window can be toggled on/off by clicking the pin marker or the close icon[cite: 6].
- Click **Back to Previous Page (返回上一頁)** to return to the results list[cite: 6].

#### B. Full Daily Route & Cost Breakdown
- Click the **Map Button (地圖)** at the top-right of the search results to view all locations on an aggregated map[cite: 6, 7].
- Below the map view, the system provides segment-by-segment travel insights[cite: 6, 9]:
  - **Taxi Fare Estimate (計程車費用):** Estimated cost in TWD (e.g., NT$70, NT$95)[cite: 9].
  - **Distance (距離):** Exact distance between consecutive stops (in meters)[cite: 9].
  - **Walking Suggestions (建議步行):** Recommended walking time where applicable (e.g., short hops of under 1 km)[cite: 9].

---

### 4. Logout & User Feedback

1. **Logging Out:** Click **Logout (登出)** in the top navigation bar from the search, result, or map screens[cite: 4, 9, 10].
2. **Confirmation:** Confirm the action in the prompt dialog[cite: 10].
3. **Satisfaction Survey (滿意度調查):**
   - Automatically directs to an anonymous survey evaluating **Interface (介面)** and **Usability (使用性)**[cite: 10, 11].
   - Users can rate experience from **Very Satisfied (非常滿意)** to **Very Dissatisfied (非常不滿意)** and enter qualitative feedback in the **Feedback (回饋)** text area[cite: 11, 12].
   - Click **Submit (確認)** to finish, or click **Back to Login (返回登入頁)** to return to the sign-in screen[cite: 10, 12].


