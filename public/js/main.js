const Toast = Swal.mixin({
  toast: true,
  position: "top-end",
  showConfirmButton: false,
  timer: 1500,
  timerProgressBar: true,
  didOpen: (toast) => {
    toast.addEventListener("mouseenter", Swal.stopTimer);
    toast.addEventListener("mouseleave", Swal.resumeTimer);
  },
});
window.addEventListener("DOMContentLoaded", () => {
  const body = document.body;

  const error = body.dataset.error;
  const success = body.dataset.success;

  if (error) {
    showAlert({ error });
  }

  if (success) {
    showAlert({ success });
  }
});
function handleServerResponse(json) {
  showAlert({
    success: json.message || "Thành công!",
  }).then(() => {
    if (json.redirect) {
      window.location.href = json.redirect;
    } else if (json.reload !== false) {
      location.reload();
    }
  });
}
function handleError(err) {
  if (err?.errors) {
    const firstError = Object.values(err.errors)[0];
    showAlert({ error: firstError });
    return;
  }

  if (err?.message) {
    showAlert({ error: err.message });
    return;
  }

  showAlert({ error: "Có lỗi xảy ra!" });
}
function showAlert({ error = null, success = null }) {
  if (error) {
    return Toast.fire("Lỗi", error, "error");
  }
  if (success) {
    return Toast.fire("Thành công", success, "success");
  }
}
document.addEventListener("submit", async (e) => {
  const form = e.target.closest(".form-post");
  if (!form) return;

  e.preventDefault();

  const formData = new FormData(form);

  try {
    const json = await fetchJSON(form.action, {
      method: "POST",
      body: formData,
    });

    handleServerResponse(json);
  } catch (err) {
    handleError(err);
  }
});
document.addEventListener("click", async (e) => {
  const btn = e.target.closest('[data-action="edit"]');
  if (!btn) return;

  const url = btn.dataset.url;
  const popupId = btn.dataset.popup;
  const callbackName = btn.dataset.callback;

  try {
    const json = await fetchJSON(url);

    if (window[callbackName]) {
      window[callbackName](json.data || json);
    }

    document.getElementById(popupId)?.classList.remove("hidden");
  } catch (err) {
    showAlert({ error: err.message || "Không lấy được dữ liệu!" });
  }
});
const formEdit = document.getElementById("formEdit");
if (formEdit) {
  formEdit.addEventListener("submit", async (e) => {
    e.preventDefault();

    const formData = new FormData(formEdit);

    try {
      const json = await fetchJSON(formEdit.action, {
        method: "POST",
        body: formData,
      });

      handleServerResponse(json);
    } catch (err) {
      handleError(err);
    }
  });
}
document.addEventListener("click", async (e) => {
  const btn = e.target.closest('[data-action="delete"]');
  if (!btn) return;

  const url = btn.dataset.url;
  const name = btn.dataset.name || "";

  const result = await Swal.fire({
    title: "Xác nhận xóa?",
    text: `Bạn có chắc muốn xóa "${name}" không?`,
    icon: "warning",
    showCancelButton: true,
    confirmButtonText: "Xóa",
    cancelButtonText: "Hủy",
    reverseButtons: true,
  });

  if (!result.isConfirmed) return;

  try {
    const json = await fetchJSON(url, {
      method: "POST",
    });

    handleServerResponse(json);
  } catch (err) {
    showAlert({ error: err.message || "Xóa thất bại!" });
  }
});

async function fetchJSON(url, options = {}) {
  const res = await fetch(url, options);

  const text = await res.text();
  console.log("RAW RESPONSE:", text);

  let json;

  // Chỉ bắt lỗi parse
  try {
    json = JSON.parse(text);
  } catch (err) {
    console.error("Parse error:", err);
    throw new Error("Server không trả JSON hợp lệ");
  }

  // Sau khi parse thành công mới xử lý logic
  if (!res.ok) {
    throw json;
  }

  if (json.success === false) {
    throw json; // vẫn throw đúng lỗi validate
  }

  return json;
}
