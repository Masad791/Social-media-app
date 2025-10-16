

document.addEventListener('DOMContentLoaded', function () {
    const menuToggle = document.getElementById('guest-menu-toggle');
    const menuClose = document.getElementById('guest-menu-close');
    const mobileMenu = document.getElementById('guest-mobile-menu');

    if (menuToggle && menuClose && mobileMenu) {
        // Hide mobile menu by default on load
        mobileMenu.classList.add('hidden');

        // Toggle menu
        menuToggle.addEventListener('click', function () {
            mobileMenu.classList.toggle('hidden');
            if (!mobileMenu.classList.contains('hidden')) {
                mobileMenu.classList.remove('translate-x-full');
                document.documentElement.style.overflowX = 'hidden';
            } else {
                mobileMenu.classList.add('translate-x-full');
                document.documentElement.style.overflowX = '';
            }
        });

        // Close menu
        menuClose.addEventListener('click', function () {
            mobileMenu.classList.add('hidden');
            mobileMenu.classList.add('translate-x-full');
            document.documentElement.style.overflowX = '';
        });

        // Close menu on outside click
        document.addEventListener('click', function (event) {
            if (mobileMenu && !mobileMenu.contains(event.target) && !menuToggle.contains(event.target) && !mobileMenu.classList.contains('hidden')) {
                mobileMenu.classList.add('hidden');
                mobileMenu.classList.add('translate-x-full');
                document.documentElement.style.overflowX = '';
            }
        });
    }
});

//Ajax load comments
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.see-more-btn').forEach(button => {
        button.addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
            const postId = this.getAttribute('data-post-id');
            const csrfToken = this.getAttribute('data-csrf');

            console.log('See more clicked for post:', postId);
            fetch(`/posts/${postId}/guest-ajax-comments`, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                },
            })
                .then(response => {
                    console.log('Response status:', response.status);
                    if (!response.ok) {
                        throw new Error('Network response was not ok: ' + response.status);
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.error) {
                        console.error('Server error:', data.error);
                        alert(data.error);
                        return;
                    }
                    const commentsList = document.querySelector(`#comments-list-${postId}`);
                    commentsList.innerHTML = data.html;

                    // Hide "See More" instead of removing
                    button.classList.add('hidden');

                    // Show "See Less"
                    const seeLessBtn = document.querySelector(`#comments-toggle-${postId} .see-less-btn`);
                    if (seeLessBtn) {
                        seeLessBtn.classList.remove('hidden');
                    }

                    console.log('Comments loaded:', data);
                })
                .catch(error => {
                    console.error('AJAX Error:', error);
                    alert('Failed to load comments. Check console for details.');
                });
        });
    });

    //see less btn 
    document.querySelectorAll('.see-less-btn').forEach(button => {
        button.addEventListener('click', function () {
            const postId = this.dataset.postId;

            // Get the comments list
            const commentsList = document.querySelector(`#comments-list-${postId}`);
            if (!commentsList) return;

            // Hide all except first comment
            const comments = commentsList.querySelectorAll('.comment');
            comments.forEach((comment, index) => {
                if (index >= 1) {
                    comment.style.display = 'none';
                } else {
                    comment.style.display = 'block';
                }
            });

            // Hide See Less, show See More
            this.classList.add('hidden');
            const seeMoreBtn = document.querySelector(`#comments-toggle-${postId} .see-more-btn`);
            if (seeMoreBtn) {
                seeMoreBtn.classList.remove('hidden');
            }
        });
    });
});

