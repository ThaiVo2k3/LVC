<div class="lg:ml-64 p-4 md:p-6 bg-gray-100 min-h-screen">

  <!-- HEADER -->
  <div class="flex justify-between items-center mb-6">
    <div>
      <h1 class="text-3xl font-bold text-gray-800">Dashboard</h1>
      <p class="text-gray-500">Thống kê hệ thống</p>
    </div>
    <div class="text-sm text-gray-400">
      <?= date("d/m/Y") ?>
    </div>
    <div class="flex gap-2">
      <button
        class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition text-sm"
        data-bs-toggle="modal"
        data-bs-target="#changePasswordModal">
        <i class="fa-solid fa-key mr-1"></i> Đổi mật khẩu
      </button>
    </div>
  </div>

  <!-- CARDS -->
  <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-5 mb-6">

    <!-- USERS -->
    <div class="bg-white p-5 rounded-2xl shadow hover:shadow-lg transition">
      <div class="flex justify-between items-center">
        <div>
          <p class="text-gray-500 text-sm">Users</p>
          <h3 class="text-2xl font-bold"><?= count($totalUsers) ?></h3>
        </div>
        <div class="bg-blue-100 text-blue-600 p-3 rounded-full">
          <i class="fa-solid fa-users"></i>
        </div>
      </div>
    </div>

    <!-- ORDERS -->
    <div class="bg-white p-5 rounded-2xl shadow hover:shadow-lg transition">
      <div class="flex justify-between items-center">
        <div>
          <p class="text-gray-500 text-sm">Orders</p>
          <h3 class="text-2xl font-bold"><?= count($totalOrders) ?></h3>
        </div>
        <div class="bg-green-100 text-green-600 p-3 rounded-full">
          <i class="fa-solid fa-cart-shopping"></i>
        </div>
      </div>
    </div>

    <!-- PRODUCTS -->
    <div class="bg-white p-5 rounded-2xl shadow hover:shadow-lg transition">
      <div class="flex justify-between items-center">
        <div>
          <p class="text-gray-500 text-sm">Products</p>
          <h3 class="text-2xl font-bold"><?= count($totalProducts) ?></h3>
        </div>
        <div class="bg-yellow-100 text-yellow-600 p-3 rounded-full">
          <i class="fa-solid fa-box"></i>
        </div>
      </div>
    </div>

    <!-- REVENUE -->
    <div class="bg-white p-5 rounded-2xl shadow hover:shadow-lg transition">
      <div class="flex justify-between items-center">
        <div>
          <p class="text-gray-500 text-sm">Revenue (7 ngày)</p>
          <h3 class="text-2xl font-bold text-green-600">
            <?= number_format(array_sum(array_column($todayRevenue, 'total'))) ?> đ
          </h3>
        </div>
        <div class="bg-red-100 text-red-600 p-3 rounded-full">
          <i class="fa-solid fa-coins"></i>
        </div>
      </div>
    </div>

  </div>

  <!-- CHARTS -->
  <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">

    <!-- LINE CHART -->
    <div class="bg-white p-5 rounded-2xl shadow">
      <h5 class="mb-4 font-semibold text-gray-700">📈 Doanh thu 7 ngày</h5>
      <canvas id="lineChart"></canvas>
    </div>

    <!-- BAR CHART -->
    <div class="bg-white p-5 rounded-2xl shadow">
      <h5 class="mb-4 font-semibold text-gray-700">📊 So sánh doanh thu</h5>
      <canvas id="barChart"></canvas>
    </div>

  </div>

</div>
<div class="modal fade" id="changePasswordModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content rounded-3">

      <!-- HEADER -->
      <div class="modal-header">
        <h5 class="modal-title">
          🔐 Đổi mật khẩu
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <!-- BODY -->
      <form class="form-post" action="/admin/change" method="post" enctype="multipart/form-data">
        <div class="modal-body">

          <!-- CURRENT PASSWORD -->
          <div class="mb-3">
            <label class="form-label">Mật khẩu hiện tại</label>
            <input type="password" name="current_password" class="form-control" required>
          </div>

          <!-- NEW PASSWORD -->
          <div class="mb-3">
            <label class="form-label">Mật khẩu mới</label>
            <input type="password" id="newPassword" name="new_password" class="form-control" required>
          </div>

          <!-- CONFIRM -->
          <div class="mb-3">
            <label class="form-label">Nhập lại mật khẩu</label>
            <input type="password" id="confirmPassword" name="confirm_password" class="form-control" required>
          </div>

          <p id="errorMsg" class="text-danger text-sm hidden"></p>

        </div>

        <!-- FOOTER -->
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
            Hủy
          </button>
          <button class="btn btn-primary">
            Cập nhật
          </button>
        </div>
      </form>

    </div>
  </div>
</div>
<script>
  const revenueData = <?= json_encode($todayRevenue) ?>;

  const labels = revenueData.map(item => item.date);
  const totals = revenueData.map(item => item.total);
</script>
<script>
  const ctx1 = document.getElementById('lineChart');

  new Chart(ctx1, {
    type: 'line',
    data: {
      labels: labels,
      datasets: [{
        label: 'Doanh thu',
        data: totals,
        borderWidth: 2,
        tension: 0.4,
        fill: true
      }]
    },
    options: {
      responsive: true,
      plugins: {
        legend: {
          display: true
        }
      }
    }
  });
</script>
<script>
  const ctx2 = document.getElementById('barChart');

  new Chart(ctx2, {
    type: 'bar',
    data: {
      labels: labels,
      datasets: [{
        label: 'Doanh thu',
        data: totals,
        borderWidth: 1
      }]
    },
    options: {
      responsive: true
    }
  });
</script>
<script>
  document.querySelectorAll(".form-post").forEach((form) => {
    form.action = `/admin/change`;
  });
</script>