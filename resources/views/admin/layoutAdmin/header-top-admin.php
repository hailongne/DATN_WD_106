<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css" rel="stylesheet">
<style>
  .header {
    background-color: #f8f9fa;
    padding: 10px 30px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
  }

  .header .title-section h1 {
    font-size: 24px;
    font-weight: bold;
    margin: 0;
    color: #343a40;
  }

  .header .icon-section {
    display: flex;
    align-items: center;
    gap: 20px;
  }

  .header .icon-section .icon {
    font-size: 20px;
    color: #6c757d;
    cursor: pointer;
    transition: color 0.3s ease;
  }

  .header .icon-section .icon:hover {
    color: #6c757d;
  }

  .header .profile-image {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    object-fit: cover;
  }

  .header .icon-section p {
    margin: 0;
    font-size: 16px;
    color: #343a40;
    font-weight: 500;
  }

  .notification-dot {
    position: relative;
  }

  .notification-dot::after {
    content: '';
    width: 10px;
    height: 10px;
    background: red;
    border-radius: 50%;
    position: absolute;
    top: -3px;
    right: -3px;
    border: 2px solid white;
  }
</style>

<div class="header">
  <div class="title-section">
    <h1>Dashboard</h1>
  </div>
  <div class="icon-section">
    <a href="/" class="icon bi bi-house" title="Trang chủ" data-bs-toggle="tooltip" data-bs-placement="bottom"></a>
    <p>Admin</p>
  </div>
</div>
