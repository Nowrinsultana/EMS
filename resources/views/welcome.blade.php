<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>PALINDROME — Employee Management System</title>
<style>
  @import url('https://fonts.googleapis.com/css2?family=Big+Shoulders+Display:wght@600;700;800&family=Inter:wght@400;500;600;700&family=IBM+Plex+Mono:wght@400;500&display=swap');

  :root{
    --bg: #EEF1F5;
    --surface: #FFFFFF;
    --ink: #121826;
    --ink-soft: #586174;
    --ink-faint: #8A93A3;
    --line: #D7DCE3;
    --accent: #C8893D;
    --accent-deep: #8B5E2B;
    --accent-tint: #F6E9D8;
    --success: #3F7A5C;
    --success-tint: #E1EEE7;
    --warn: #B8762D;
    --warn-tint: #F4E6D3;
    --display: "Big Shoulders Display", sans-serif;
    --body: "Inter", sans-serif;
    --mono: "IBM Plex Mono", monospace;
  }

  *{ box-sizing: border-box; }
  html{ scroll-behavior: smooth; }
  body{
    margin:0;
    background: var(--bg);
    color: var(--ink);
    font-family: var(--body);
    line-height: 1.5;
    -webkit-font-smoothing: antialiased;
  }
  img,svg{ display:block; max-width:100%; }
  a{ color:inherit; text-decoration:none; }
  button{ font-family: inherit; cursor:pointer; }

  :focus-visible{
    outline: 2px solid var(--accent-deep);
    outline-offset: 3px;
  }

  .wrap{
    max-width: 1180px;
    margin: 0 auto;
    padding: 0 32px;
  }

  /* ---------- NAV ---------- */
  header.site{
    position: sticky;
    top:0;
    z-index: 50;
    background: rgba(238,241,245,0.88);
    backdrop-filter: blur(10px);
    border-bottom: 1px solid var(--line);
  }
  .nav{
    display:flex;
    align-items:center;
    justify-content:space-between;
    padding: 18px 32px;
    max-width: 1180px;
    margin: 0 auto;
  }
  .brand{
    display:flex;
    align-items:center;
    gap:10px;
    font-family: var(--display);
    font-weight: 800;
    font-size: 22px;
    letter-spacing: 0.01em;
  }
  .brand .logo-mark{
    flex-shrink: 0;
    border-radius: 7px;
  }
  nav.links{
    display:flex;
    gap: 32px;
    font-size: 14.5px;
    font-weight: 500;
    color: var(--ink-soft);
  }
  nav.links a:hover{ color: var(--ink); }
  .nav-cta{
    display:flex;
    align-items:center;
    gap: 18px;
  }
  .nav-cta .signin{
    font-size: 14.5px;
    font-weight: 600;
    color: var(--ink-soft);
  }
  .nav-cta .signin:hover{ color: var(--ink); }

  .btn{
    display:inline-flex;
    align-items:center;
    justify-content:center;
    gap:8px;
    padding: 11px 20px;
    border-radius: 7px;
    font-weight: 600;
    font-size: 14.5px;
    border: 1px solid transparent;
    transition: transform .15s ease, box-shadow .15s ease, background .15s ease;
  }
  .btn-primary{
    background: var(--ink);
    color: #fff;
  }
  .btn-primary:hover{ background: #232b3d; }
  .btn-primary:active{ transform: translateY(1px); }
  .btn-ghost{
    background: transparent;
    border-color: var(--line);
    color: var(--ink);
  }
  .btn-ghost:hover{ border-color: var(--ink-faint); background: #fff; }

  .menu-toggle{ display:none; }

  /* ---------- HERO ---------- */
  .hero{
    padding: 86px 0 70px;
  }
  .hero-grid{
    display:grid;
    grid-template-columns: 1.05fr 0.95fr;
    gap: 64px;
    align-items: center;
  }
  .eyebrow{
    display:inline-flex;
    align-items:center;
    gap:8px;
    font-family: var(--mono);
    font-size: 12px;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    color: var(--accent-deep);
    background: var(--accent-tint);
    padding: 6px 12px;
    border-radius: 100px;
    margin-bottom: 22px;
  }
  h1.hero-title{
    font-family: var(--display);
    font-weight: 800;
    font-size: clamp(40px, 5.2vw, 64px);
    line-height: 0.98;
    letter-spacing: -0.01em;
    margin: 0 0 22px;
  }
  h1.hero-title em{
    font-style: normal;
    color: var(--accent-deep);
  }
  .hero-sub{
    font-size: 17.5px;
    color: var(--ink-soft);
    max-width: 470px;
    margin: 0 0 32px;
  }
  .hero-actions{
    display:flex;
    align-items:center;
    gap: 18px;
    margin-bottom: 36px;
  }
  .btn-lg{ padding: 14px 26px; font-size: 15.5px; }
  .hero-note{
    font-size: 13.5px;
    color: var(--ink-faint);
  }
  .avatars{
    display:flex;
    margin-top: 18px;
    align-items:center;
    gap: 12px;
  }
  .avatar-stack{ display:flex; }
  .avatar-stack span{
    width:30px; height:30px;
    border-radius: 50%;
    border: 2px solid var(--bg);
    margin-left: -8px;
    background: linear-gradient(135deg, #cdd5e0, #aab4c4);
  }
  .avatar-stack span:first-child{ margin-left:0; }

  /* ---- Signature: Roster board ---- */
  .board{
    background: var(--surface);
    border: 1px solid var(--line);
    border-radius: 14px;
    box-shadow: 0 1px 2px rgba(18,24,38,0.04), 0 18px 40px -20px rgba(18,24,38,0.18);
    overflow: hidden;
    transform: rotate(0.4deg);
  }
  .board-head{
    display:flex;
    align-items:center;
    justify-content:space-between;
    padding: 16px 20px;
    border-bottom: 1px solid var(--line);
  }
  .board-head .title{
    font-weight: 700;
    font-size: 14.5px;
  }
  .board-head .synced{
    font-family: var(--mono);
    font-size: 11.5px;
    color: var(--ink-faint);
  }
  .board-row{
    display:grid;
    grid-template-columns: 34px 1fr auto auto;
    align-items:center;
    gap: 14px;
    padding: 13px 20px;
    border-bottom: 1px solid #F0F2F5;
  }
  .board-row:last-child{ border-bottom:none; }
  .avatar-sm{
    width:30px; height:30px;
    border-radius: 50%;
    background: linear-gradient(135deg, #dfe4ea, #c2cad6);
    display:flex; align-items:center; justify-content:center;
    font-family: var(--mono);
    font-size: 11px;
    color: var(--ink-soft);
    font-weight: 500;
  }
  .row-name{ font-size: 14px; font-weight: 600; }
  .row-role{ font-size: 12px; color: var(--ink-faint); margin-top: 1px; }
  .status{
    display:flex;
    align-items:center;
    gap:7px;
    font-family: var(--mono);
    font-size: 11.5px;
    padding: 4px 9px;
    border-radius: 100px;
    white-space: nowrap;
  }
  .status .pulse{
    width:7px; height:7px;
    border-radius: 50%;
    flex-shrink:0;
  }
  .status.in{ background: var(--success-tint); color: var(--success); }
  .status.in .pulse{ background: var(--success); animation: pulse 1.8s infinite; }
  .status.out{ background: #EEF0F3; color: var(--ink-faint); }
  .status.out .pulse{ background: var(--ink-faint); }
  .status.leave{ background: var(--warn-tint); color: var(--warn); }
  .status.leave .pulse{ background: var(--warn); }
  .row-time{
    font-family: var(--mono);
    font-size: 12px;
    color: var(--ink-faint);
    min-width: 56px;
    text-align:right;
  }
  @keyframes pulse{
    0%{ box-shadow: 0 0 0 0 rgba(63,122,90,0.45); }
    70%{ box-shadow: 0 0 0 5px rgba(63,122,90,0); }
    100%{ box-shadow: 0 0 0 0 rgba(63,122,90,0); }
  }
  .board-foot{
    padding: 13px 20px;
    background: #FAFBFC;
    display:flex;
    align-items:center;
    justify-content:space-between;
    font-size: 12.5px;
    color: var(--ink-soft);
  }
  .board-foot strong{ color: var(--ink); font-family: var(--mono); font-weight: 500; }

  @media (prefers-reduced-motion: reduce){
    .status .pulse{ animation: none !important; }
  }

  /* ---------- LOGO BAND ---------- */
  .logoband{
    border-top: 1px solid var(--line);
    border-bottom: 1px solid var(--line);
    padding: 28px 0;
  }
  .logoband .wrap{
    display:flex;
    align-items:center;
    justify-content:space-between;
    flex-wrap: wrap;
    gap: 24px;
  }
  .logoband .label{
    font-family: var(--mono);
    font-size: 11.5px;
    text-transform: uppercase;
    letter-spacing: 0.08em;
    color: var(--ink-faint);
    flex-shrink:0;
  }
  .logo-row{
    display:flex;
    gap: 40px;
    flex-wrap: wrap;
    font-family: var(--display);
    font-weight: 700;
    font-size: 19px;
    color: var(--ink-faint);
    letter-spacing: 0.01em;
  }

  /* ---------- SECTION SHARED ---------- */
  section{ padding: 96px 0; }
  .section-head{
    max-width: 560px;
    margin-bottom: 56px;
  }
  .section-tag{
    font-family: var(--mono);
    font-size: 12px;
    text-transform: uppercase;
    letter-spacing: 0.08em;
    color: var(--accent-deep);
    margin-bottom: 14px;
    display:block;
  }
  h2.section-title{
    font-family: var(--display);
    font-weight: 800;
    font-size: clamp(30px, 3.6vw, 42px);
    line-height: 1.05;
    margin: 0 0 16px;
    letter-spacing: -0.01em;
  }
  .section-desc{
    color: var(--ink-soft);
    font-size: 16px;
  }

  /* ---------- FEATURES ---------- */
  .feature-grid{
    display:grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1px;
    background: var(--line);
    border: 1px solid var(--line);
    border-radius: 14px;
    overflow: hidden;
  }
  .feature{
    background: var(--surface);
    padding: 32px 28px;
  }
  .feature .ficon{
    width: 38px; height: 38px;
    border-radius: 9px;
    background: var(--accent-tint);
    display:flex; align-items:center; justify-content:center;
    margin-bottom: 20px;
  }
  .feature .ficon svg{ width:18px; height:18px; stroke: var(--accent-deep); }
  .feature h3{
    font-size: 17px;
    font-weight: 700;
    margin: 0 0 8px;
  }
  .feature p{
    font-size: 14.5px;
    color: var(--ink-soft);
    margin: 0;
  }

  /* ---------- HOW IT WORKS ---------- */
  .flow{
    background: var(--ink);
    color: #fff;
  }
  .flow .section-tag{ color: var(--accent); }
  .flow .section-desc{ color: #AAB2C2; }
  .flow h2.section-title{ color: #fff; }
  .flow-steps{
    display:grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 28px;
  }
  .flow-step{
    border-top: 2px solid #2B3242;
    padding-top: 22px;
  }
  .flow-step .num{
    font-family: var(--mono);
    font-size: 13px;
    color: var(--accent);
    margin-bottom: 14px;
    display:block;
  }
  .flow-step h3{
    font-size: 17px;
    margin: 0 0 8px;
    font-weight: 700;
  }
  .flow-step p{
    font-size: 14px;
    color: #AAB2C2;
    margin:0;
  }

  /* ---------- METRICS ---------- */
  .metrics{
    display:grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 32px;
    border-top: 1px solid var(--line);
    border-bottom: 1px solid var(--line);
    padding: 56px 0;
  }
  .metric .num{
    font-family: var(--display);
    font-weight: 800;
    font-size: 42px;
    color: var(--ink);
  }
  .metric .num span{ color: var(--accent-deep); }
  .metric .lbl{
    font-size: 13.5px;
    color: var(--ink-soft);
    margin-top: 6px;
  }

  /* ---------- TESTIMONIAL ---------- */
  .testimonial{
    background: var(--surface);
    border: 1px solid var(--line);
    border-radius: 16px;
    padding: 48px;
    display:grid;
    grid-template-columns: auto 1fr;
    gap: 36px;
    align-items:center;
  }
  .testimonial .mark{
    font-family: var(--display);
    font-size: 64px;
    color: var(--accent-tint);
    line-height: 1;
  }
  .testimonial blockquote{
    margin: 0 0 18px;
    font-size: 20px;
    line-height: 1.4;
    font-weight: 500;
    letter-spacing: -0.005em;
  }
  .testimonial figcaption{
    font-size: 14px;
    color: var(--ink-soft);
  }
  .testimonial figcaption strong{ color: var(--ink); }

  /* ---------- CTA BANNER ---------- */
  .cta-banner{
    background: linear-gradient(135deg, var(--ink) 0%, #232C42 100%);
    border-radius: 18px;
    padding: 64px;
    display:flex;
    align-items:center;
    justify-content:space-between;
    gap: 32px;
    color:#fff;
  }
  .cta-banner h2{
    font-family: var(--display);
    font-weight: 800;
    font-size: clamp(26px, 3.4vw, 36px);
    margin: 0 0 10px;
    letter-spacing: -0.01em;
  }
  .cta-banner p{
    color: #AAB2C2;
    margin:0;
    font-size: 15.5px;
  }
  .cta-banner .actions{ display:flex; gap:14px; flex-shrink:0; }
  .btn-on-dark{ background:#fff; color: var(--ink); }
  .btn-on-dark:hover{ background: #EDEDED; }
  .btn-ghost-dark{ border-color: #3A4257; color:#fff; }
  .btn-ghost-dark:hover{ background: rgba(255,255,255,0.06); border-color:#56607a; }

  /* ---------- FOOTER ---------- */
  footer{
    border-top: 1px solid var(--line);
    padding: 56px 0 36px;
  }
  .footer-top{
    display:grid;
    grid-template-columns: 1.4fr 1fr 1fr 1fr;
    gap: 32px;
    margin-bottom: 48px;
  }
  .footer-brand .brand{ margin-bottom: 14px; }
  .footer-brand p{
    font-size: 14px;
    color: var(--ink-soft);
    max-width: 260px;
  }
  .footer-col h4{
    font-size: 13px;
    text-transform: uppercase;
    letter-spacing: 0.06em;
    color: var(--ink-faint);
    margin: 0 0 16px;
    font-weight: 600;
  }
  .footer-col a{
    display:block;
    font-size: 14.5px;
    color: var(--ink-soft);
    margin-bottom: 11px;
  }
  .footer-col a:hover{ color: var(--ink); }
  .footer-bottom{
    display:flex;
    justify-content:space-between;
    align-items:center;
    padding-top: 28px;
    border-top: 1px solid var(--line);
    font-size: 13px;
    color: var(--ink-faint);
  }

  /* ---------- RESPONSIVE ---------- */
  @media (max-width: 920px){
    nav.links{ display:none; }
    .hero-grid{ grid-template-columns: 1fr; gap: 48px; }
    .feature-grid{ grid-template-columns: 1fr 1fr; }
    .flow-steps{ grid-template-columns: 1fr 1fr; }
    .metrics{ grid-template-columns: 1fr 1fr; gap: 28px; }
    .footer-top{ grid-template-columns: 1fr 1fr; }
    .testimonial{ grid-template-columns: 1fr; padding: 36px; }
    .cta-banner{ flex-direction: column; align-items:flex-start; padding: 40px; }
  }
  @media (max-width: 600px){
    .wrap{ padding: 0 20px; }
    .nav{ padding: 16px 20px; }
    .feature-grid{ grid-template-columns: 1fr; }
    .flow-steps{ grid-template-columns: 1fr; }
    .metrics{ grid-template-columns: 1fr 1fr; }
    .hero{ padding: 56px 0 48px; }
    section{ padding: 64px 0; }
    .cta-banner .actions{ flex-direction: column; width: 100%; }
    .cta-banner .actions .btn{ width: 100%; }
  }
</style>
</head>
<body>

<header class="site">
  <div class="nav">
    <a href="#" class="brand"><svg class="logo-mark" viewBox="0 0 32 32" width="28" height="28" aria-hidden="true"><rect x="0" y="0" width="32" height="32" rx="8" fill="#121826"/><path d="M5 9 L16 16 L5 23 Z" fill="#C8893D"/><path d="M27 9 L16 16 L27 23 Z" fill="#FFFFFF"/><line x1="16" y1="6" x2="16" y2="26" stroke="#C8893D" stroke-width="1" stroke-opacity="0.5"/></svg>PALINDROME</a>
    <nav class="links">
      <a href="#features">Product</a>
      <a href="#flow">How it works</a>
      <a href="#metrics">Customers</a>
      <a href="{{ route('jobs.list') }}">Jobs</a>
    </nav>
    <div class="nav-cta">
      <a href="{{ route('login') }}" class="signin">Sign in</a>
    </div>
  </div>
</header>

<main>
  <section class="hero">
    <div class="wrap hero-grid">
      <div>
        <span class="eyebrow">● Employee Management System</span>
        <h1 class="hero-title">Manage your workforce<br>with <em>PALINDROME.</em></h1>
        <p class="hero-sub">Departments, employees, attendance, leave, and payroll — all in one place. Built for teams who need structure without the complexity.</p>
        <div class="hero-actions">
          <a href="{{ route('login') }}" class="btn btn-primary btn-lg">Sign in</a>
          <a href="{{ route('jobs.list') }}" class="btn btn-ghost btn-lg">View open positions</a>
        </div>
        <div class="avatars">
          <div class="avatar-stack">
            <span></span><span></span><span></span><span></span>
          </div>
          <span class="hero-note">Used by departments of all sizes</span>
        </div>
      </div>

      <div class="board" id="board">
        <div class="board-head">
          <span class="title">Today's attendance — Downtown branch</span>
          <span class="synced" id="synced">synced 0s ago</span>
        </div>

        <div class="board-row">
          <div class="avatar-sm">MN</div>
          <div>
            <div class="row-name">Maya Nakamura</div>
            <div class="row-role">Shift lead</div>
          </div>
          <span class="status in"><span class="pulse"></span>Clocked in</span>
          <span class="row-time">08:02</span>
        </div>
        <div class="board-row">
          <div class="avatar-sm">DO</div>
          <div>
            <div class="row-name">Diego Ortiz</div>
            <div class="row-role">Line cook</div>
          </div>
          <span class="status in"><span class="pulse"></span>Clocked in</span>
          <span class="row-time">07:58</span>
        </div>
        <div class="board-row">
          <div class="avatar-sm">RK</div>
          <div>
            <div class="row-name">Reema Khan</div>
            <div class="row-role">Cashier</div>
          </div>
          <span class="status leave"><span class="pulse"></span>On leave</span>
          <span class="row-time">—</span>
        </div>
        <div class="board-row">
          <div class="avatar-sm">TS</div>
          <div>
            <div class="row-name">Tom Sullivan</div>
            <div class="row-role">Stock</div>
          </div>
          <span class="status out"><span class="pulse"></span>Scheduled 2pm</span>
          <span class="row-time">14:00</span>
        </div>

        <div class="board-foot">
          <span>3 of 4 on shift</span>
          <span><strong>$1,204.50</strong> hours logged today</span>
        </div>
      </div>
    </div>
  </section>

  <div class="logoband">
    <div class="wrap">
      <span class="label">Running the floor at</span>
      <div class="logo-row">
        <span>Northbridge</span>
        <span>Fielder &amp; Co</span>
        <span>Maple Logistics</span>
        <span>Harlow Retail</span>
        <span>Crestpoint</span>
      </div>
    </div>
  </div>

  <section id="features">
    <div class="wrap">
      <div class="section-head">
        <span class="section-tag">What it does</span>
        <h2 class="section-title">Everything between hiring and paying, in one place.</h2>
        <p class="section-desc">PALINDROME brings departments, employees, attendance, leave, and payroll together — no more jumping between tools.</p>
      </div>

      <div class="feature-grid">
        <div class="feature">
          <div class="ficon"><svg viewBox="0 0 24 24" fill="none" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M16 3.5a4 4 0 110 8 4 4 0 010-8zM2 20.5c0-4 3-7 6-7s6 3 6 7M14 13.5c2.5 0 5 2.5 5 6"/></svg></div>
          <h3>Employee directory</h3>
          <p>Every staff member's role, contact, documents, and leave history — searchable in seconds.</p>
        </div>
        <div class="feature">
          <div class="ficon"><svg viewBox="0 0 24 24" fill="none" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4.5" width="18" height="16" rx="2"/><path d="M3 9.5h18M8 2.5v4M16 2.5v4"/></svg></div>
          <h3>Leave management</h3>
          <p>Request, approve, or decline time off in one click. Leave balances update automatically as approvals roll in.</p>
        </div>
        <div class="feature">
          <div class="ficon"><svg viewBox="0 0 24 24" fill="none" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3.5 2"/></svg></div>
          <h3>Attendance tracking</h3>
          <p>QR code check-in and check-out. Daily logs, monthly summaries, and real-time status at a glance.</p>
        </div>
        <div class="feature">
          <div class="ficon"><svg viewBox="0 0 24 24" fill="none" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 7h18M3 12h18M3 17h11"/></svg></div>
          <h3>Payroll</h3>
          <p>Basic salaries, adjustments, and payslips — calculated per cycle. Everything flows from a single source of truth.</p>
        </div>
        <div class="feature">
          <div class="ficon"><svg viewBox="0 0 24 24" fill="none" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3l2.5 5 5.5.8-4 4 1 5.4L12 15.7 6.9 18.2l1-5.4-4-4 5.5-.8L12 3z"/></svg></div>
          <h3>Recruitment</h3>
          <p>Post vacancies, accept applications, schedule interviews — all within the same system your team already uses.</p>
        </div>
        <div class="feature">
          <div class="ficon"><svg viewBox="0 0 24 24" fill="none" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19h16M4 19V9.5l5-4 5 4V19M14 19V13h5v6"/></svg></div>
          <h3>Multi-department</h3>
          <p>Superusers oversee everything. Department admins manage their own teams. Staff access their own records. No overlap.</p>
        </div>
      </div>
    </div>
  </section>

  <section class="flow" id="flow">
    <div class="wrap">
      <div class="section-head">
        <span class="section-tag">Getting set up</span>
        <h2 class="section-title">Set up in minutes, not months.</h2>
        <p class="section-desc">Create departments, add employees, and go live. No training sessions or onboarding calls needed.</p>
      </div>
      <div class="flow-steps">
        <div class="flow-step">
          <span class="num">01</span>
          <h3>Create departments</h3>
          <p>Set up departments and assign admins. Each department operates independently with its own staff and data.</p>
        </div>
        <div class="flow-step">
          <span class="num">02</span>
          <h3>Add employees</h3>
          <p>Register staff members with roles and details. Each employee gets a secure password setup link.</p>
        </div>
        <div class="flow-step">
          <span class="num">03</span>
          <h3>Manage operations</h3>
          <p>Handle leave requests, track daily attendance via QR codes, and oversee recruitment from one dashboard.</p>
        </div>
        <div class="flow-step">
          <span class="num">04</span>
          <h3>Run payroll</h3>
          <p>Set salaries, apply adjustments, and generate payslips — calculated automatically from your employee data.</p>
        </div>
      </div>
    </div>
  </section>

  <section id="metrics">
    <div class="wrap">
      <div class="metrics">
        <div class="metric">
          <div class="num"><span>100</span>%</div>
          <div class="lbl">Cloud-based, zero setup</div>
        </div>
        <div class="metric">
          <div class="num"><span>3</span>×</div>
          <div class="lbl">Faster leave approvals</div>
        </div>
        <div class="metric">
          <div class="num"><span>1</span></div>
          <div class="lbl">System for all HR operations</div>
        </div>
        <div class="metric">
          <div class="num"><span>24/7</span></div>
          <div class="lbl">Access from anywhere</div>
        </div>
      </div>
    </div>
  </section>

  <section>
    <div class="wrap">
      <figure class="testimonial">
        <span class="mark">"</span>
        <div>
          <blockquote>We went from chasing paper leave forms and manual spreadsheets to a single dashboard. PALINDROME streamlined our entire HR workflow in one day.</blockquote>
          <figcaption><strong>Sarah Almeida</strong> — HR Director, Northbridge Group</figcaption>
        </div>
      </figure>
    </div>
  </section>


</main>

<footer>
  <div class="wrap">
    <div class="footer-top">
      <div class="footer-brand">
        <a href="#" class="brand"><svg class="logo-mark" viewBox="0 0 32 32" width="28" height="28" aria-hidden="true"><rect x="0" y="0" width="32" height="32" rx="8" fill="#121826"/><path d="M5 9 L16 16 L5 23 Z" fill="#C8893D"/><path d="M27 9 L16 16 L27 23 Z" fill="#FFFFFF"/><line x1="16" y1="6" x2="16" y2="26" stroke="#C8893D" stroke-width="1" stroke-opacity="0.5"/></svg>PALINDROME</a>
        <p>Employee management for modern teams — departments, attendance, leave, and payroll in one place.</p>
      </div>
      <div class="footer-col">
        <h4>Product</h4>
        <a href="#">Departments</a>
        <a href="#">Attendance</a>
        <a href="#">Leave</a>
        <a href="#">Payroll</a>
      </div>
      <div class="footer-col">
        <h4>Company</h4>
        <a href="#">About</a>
        <a href="#">Careers</a>
        <a href="#">Customers</a>
        <a href="#">Contact</a>
      </div>
      <div class="footer-col">
        <h4>Resources</h4>
        <a href="#">Help center</a>
        <a href="#">Guides</a>
        <a href="#">API docs</a>
        <a href="#">Status</a>
      </div>
    </div>
    <div class="footer-bottom">
      <span>© 2026 PALINDROME, Inc.</span>
      <span>Privacy · Terms</span>
    </div>
  </div>
</footer>

<script>
  // Signature element: ticking "last synced" timestamp on the roster board
  let seconds = 0;
  const syncedEl = document.getElementById('synced');
  setInterval(() => {
    seconds += 1;
    syncedEl.textContent = 'synced ' + seconds + 's ago';
  }, 1000);
</script>

</body>
</html>