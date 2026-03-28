<div class="lg:ml-64 min-h-screen bg-gray-100 p-4">
    <div class="bg-white rounded-xl shadow overflow-x-auto">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Tên</th>
                    <th>số điên thoại</th>
                    <th>email</th>
                    <th>ngày tạo</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?= e($user['id']) ?></td>
                        <td><?= e($user['name']) ?></td>
                        <td><?= e($user['phone']) ?></td>
                        <td><?= e($user['email']) ?></td>
                        <td><?= e($user['created_at']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>