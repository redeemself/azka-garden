document.addEventListener('DOMContentLoaded', function() {
    // LIKE PRODUK
    document.querySelectorAll('.like-product-btn').forEach(function(btn) {
        let isProcessing = false;
        btn.addEventListener('click', function(e) {
            if (btn.hasAttribute('disabled') || isProcessing) return;
            e.preventDefault();
            isProcessing = true;
            const productId = btn.getAttribute('data-product-id');
            fetch(`/products/${productId}/like`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ product_id: productId })
            })
            .then(response => response.json())
            .then(data => {
                if (data.likeCount !== undefined && data.success) {
                    document.querySelectorAll('.like-count').forEach(function(span){
                        span.textContent = data.likeCount;
                    });
                    btn.classList.add('bg-green-200', 'font-bold');
                    btn.setAttribute('disabled', 'disabled');
                    btn.setAttribute('aria-disabled', 'true');
                    // Optional: feedback to user
                    alert('Berhasil like! Total Like: ' + data.likeCount);
                } else if (data.error) {
                    alert('Error: ' + data.error + ' (Total Like: ' + (data.likeCount ?? '-') + ')');
                }
            })
            .catch(() => {
                alert('Terjadi kesalahan, silakan coba lagi.');
            })
            .finally(() => {
                isProcessing = false;
            });
        });
    });

    // KOMENTAR PRODUK (AJAX, jika ingin tanpa reload)
    document.querySelectorAll('.comment-form').forEach(function(form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const productId = form.querySelector('input[name="product_id"]').value;
            const comment = form.querySelector('textarea[name="comment"]').value;
            fetch(`/products/${productId}/comment`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ product_id: productId, comment: comment })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const commentList = document.querySelector('.comment-list');
                    const newComment = document.createElement('div');
                    newComment.className = "p-4 bg-white rounded-lg border border-green-100 shadow flex flex-col";
                    newComment.innerHTML = '<div class="flex items-center gap-2 mb-1">'
                        + `<span class="font-bold text-green-800">${data.user ?? 'User'}</span>`
                        + `<span class="text-xs text-gray-400">${data.created_at ?? 'baru saja'}</span>`
                        + '</div><div class="text-gray-700">' + (data.comment ?? comment) + '</div>';
                    commentList.prepend(newComment);
                    form.reset();
                } else if (data.error) {
                    alert('Error: ' + data.error);
                }
            })
            .catch(() => {
                alert('Terjadi kesalahan, silakan coba lagi.');
            });
        });
    });

    // SHARE PRODUK
    window.shareProduct = function() {
        const title = document.querySelector('h1')?.innerText || document.title;
        const text = document.querySelector('.prose')?.innerText || 'Cek produk ini di Azka Garden!';
        const url = window.location.href;
        if (navigator.share) {
            navigator.share({ title, text, url })
                .catch(() => alert('Share gagal!'));
        } else if (navigator.clipboard) {
            navigator.clipboard.writeText(url)
                .then(() => alert('Link produk telah disalin ke clipboard!'))
                .catch(() => alert('Gagal menyalin link. Silakan copy manual.'));
        } else {
            prompt('Salin link produk:', url);
        }
    };
});
