import './bootstrap';
import Alpine from 'alpinejs';
import VanillaTilt from 'vanilla-tilt';
window.Alpine = Alpine;
Alpine.start();


VanillaTilt.init(document.querySelector(".profile-card"), {
    max: 7,
    speed: 400,
    glare: true,
    "max-glare": 0.15,
});


//-------Like and Comment ajax----//
document.addEventListener('DOMContentLoaded', function () {

    // Success message

    const successMessage = document.getElementById('success-message');
    if (successMessage) {
        setTimeout(() => {
            successMessage.style.transition = 'opacity 0.5s ease';
            successMessage.style.opacity = '0';
            setTimeout(() => successMessage.remove(), 500);
        }, 3500);
    }


    // Handle like buttons
    document.querySelectorAll('.like-btn').forEach(button => {
        button.addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
            const postId = this.getAttribute('data-post-id');
            const csrfToken = this.getAttribute('data-csrf');
            const isLiked = this.getAttribute('data-liked') === 'true';

            console.log('Like clicked for post:', postId, 'Currently liked:', isLiked);
            fetch(`/posts/${postId}/ajax-like`, {
                method: 'POST',
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
                    const likeCount = document.querySelector(`#post-${postId} .like-count`);
                    const heart = document.querySelector(`#post-${postId} .heart`);
                    likeCount.textContent = data.like_count;
                    const newLikedState = data.action === 'liked';
                    heart.textContent = newLikedState ? '❤️' : '🤍';
                    heart.classList.toggle('liked', newLikedState);
                    button.setAttribute('data-liked', newLikedState ? 'true' : 'false');
                    console.log('Like updated:', data);
                })
                .catch(error => {
                    console.error('AJAX Error:', error);
                    alert('Failed to update like. Check console for details.');
                });
        });
    });

    // Handle comment forms
    document.querySelectorAll('.comment-form').forEach(form => {
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            e.stopPropagation();
            const postId = this.getAttribute('data-post-id');
            const csrfToken = this.getAttribute('data-csrf');
            const commentInput = this.querySelector('.comment-input');
            const commentText = commentInput.value.trim();

            if (!commentText) {
                alert('Comment cannot be empty');
                return;
            }

            console.log('Comment submitted for post:', postId);
            fetch(`/posts/${postId}/ajax-comment`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                },
                body: JSON.stringify({ comment: commentText }),
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
                    const commentCount = document.querySelector(`#post-${postId} .comment-count`);
                    const newComment = document.createElement('div');
                    newComment.classList.add('comment');
                    newComment.id = `comment-${data.comment.id}`;
                    newComment.innerHTML = `
                          <span class="username">${data.comment.user_name}</span>: ${data.comment.comment}
                          <small>${data.comment.created_at}</small>
                          <button type="button" class="delete-comment-btn" data-comment-id="${data.comment.id}" data-post-id="${postId}" data-csrf="${csrfToken}">Delete</button>
                      `;
                    commentsList.insertBefore(newComment, commentsList.firstChild);
                    commentCount.textContent = data.comment_count;
                    commentInput.value = '';
                    console.log('Comment added:', data);
                    attachDeleteListeners();
                })
                .catch(error => {
                    console.error('AJAX Error:', error);
                    alert('Failed to add comment. Check console for details.');
                });
        });
    });

    // Delete comment with undo
    function attachDeleteListeners() {
        document.querySelectorAll('.delete-comment-btn').forEach(button => {
            button.removeEventListener('click', handleDeleteComment);
            button.addEventListener('click', handleDeleteComment);
        });
    }

    function handleDeleteComment(e) {
        e.preventDefault();
        e.stopPropagation();
        const button = this;
        const commentId = button.getAttribute('data-comment-id');
        const postId = button.getAttribute('data-post-id');
        const csrfToken = button.getAttribute('data-csrf');

        // Hide the comment and show undo message
        const commentElement = document.querySelector(`#comment-${commentId}`);
        const commentContainer = commentElement.parentElement;
        commentElement.style.display = 'none';

        // Create undo message
        const undoMessage = document.createElement('div');
        undoMessage.className = 'undo-message bg-yellow-100 p-3 rounded text-gray-800 mb-3';
        undoMessage.innerHTML = `
            Comment deleted. 
            <button type="button" class="undo-btn text-blue-500 hover:text-blue-700 font-semibold">Undo</button>
        `;
        commentContainer.insertBefore(undoMessage, commentElement);

        // Update comment count
        const commentCount = document.querySelector(`#post-${postId} .comment-count`);
        const originalCount = parseInt(commentCount.textContent);
        commentCount.textContent = originalCount - 1;

        // Set timeout for deletion
        const timeout = setTimeout(() => {
            console.log('Deleting comment:', commentId);
            fetch(`/comments/${commentId}`, {
                method: 'DELETE',
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
                        // Restore comment on error
                        commentElement.style.display = 'block';
                        undoMessage.remove();
                        commentCount.textContent = originalCount;
                        return;
                    }
                    // Permanently remove comment and undo message
                    commentElement.remove();
                    undoMessage.remove();
                    console.log('Comment deleted:', data);
                })
                .catch(error => {
                    console.error('AJAX Error:', error);
                    alert('Failed to delete comment. Check console for details.');
                    // Restore comment on error
                    commentElement.style.display = 'block';
                    undoMessage.remove();
                    commentCount.textContent = originalCount;
                });
        }, 3000);

        // Handle undo click
        undoMessage.querySelector('.undo-btn').addEventListener('click', () => {
            clearTimeout(timeout);
            commentElement.style.display = 'block';
            undoMessage.remove();
            commentCount.textContent = originalCount;
            console.log('Comment deletion undone:', commentId);
        });
    }


    // Handle see more comments
    document.querySelectorAll('.see-more-btn').forEach(button => {
        button.addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
            const postId = this.getAttribute('data-post-id');
            const csrfToken = this.getAttribute('data-csrf');

            console.log('See more clicked for post:', postId);
            fetch(`/posts/${postId}/ajax-comments`, {
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
                    attachDeleteListeners();
                })
                .catch(error => {
                    console.error('AJAX Error:', error);
                    alert('Failed to load comments. Check console for details.');
                });
        });
    });

    // Handle see less comments
    document.querySelectorAll('.see-less-btn').forEach(button => {
        button.addEventListener('click', function () {
            const postId = this.dataset.postId;

            // Get the comments list
            const commentsList = document.querySelector(`#comments-list-${postId}`);
            if (!commentsList) return;

            // Hide all except first 2
            const comments = commentsList.querySelectorAll('.comment');
            comments.forEach((comment, index) => {
                if (index >= 1) {
                    comment.style.display = 'none';
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

    attachDeleteListeners();
});





// // Save edited comment
// document.addEventListener('click', async function(e) {
//     if (e.target.classList.contains('save-comment-btn')) {
//         e.preventDefault();
//         const commentId = e.target.dataset.commentId;
//         const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
//         const textarea = document.querySelector(`#comment-${commentId} textarea`);
//         const newComment = textarea.value.trim();
//         if (!newComment) {
//             alert('Comment cannot be empty');
//             return;
//         }
//         try {
//             const response = await fetch(`/comments/${commentId}`, {
//                 method: 'POST',
//                 headers: {
//                     'Content-Type': 'application/json',
//                     'X-Requested-With': 'XMLHttpRequest',
//                     'X-CSRF-TOKEN': csrfToken,
//                     'Accept': 'application/json',
//                 },
//                 body: JSON.stringify({ comment: newComment }),
//             });
//             if (!response.ok) {
//                 console.log('Response status:', response.status);
//                 if (response.status === 401 || response.status === 403) {
//                     alert('Please log in to save the comment.');
//                     return;
//                 }
//                 throw new Error('Request failed');
//             }
//             const data = await response.json();
//             if (!data.success) {
//                 alert('Error: ' + (data.error || 'Unable to save comment'));
//                 return;
//             }
//             const textSpan = document.createElement('span');
//             textSpan.className = 'comment-text';
//             textSpan.textContent = data.comment;
//             textarea.replaceWith(textSpan);
//             e.target.textContent = 'Edit';
//             e.target.classList.remove('save-comment-btn');
//             e.target.classList.add('edit-comment-btn');
//         } catch (err) {
//             console.error('Error:', err);
//             alert('Failed to save comment. Please try again or refresh the page.');
//         }
//     }
// });

// Like comment
document.addEventListener('click', async function (e) {
    if (e.target.closest('.like-comment-btn')) {
        e.preventDefault();
        const button = e.target.closest('.like-comment-btn');
        const commentId = button.dataset.commentId;
        const csrfToken = button.dataset.csrf;
        try {
            const response = await fetch(`/comments/${commentId}/like`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                },
            });
            console.log('Like comment response:', {
                status: response.status,
                ok: response.ok,
                headers: [...response.headers],
            });
            const text = await response.text();
            console.log('Raw response:', text);
            if (!response.ok) {
                throw new Error(`HTTP error! Status: ${response.status}`);
            }
            let data;
            try {
                data = JSON.parse(text);
            } catch (err) {
                console.error('JSON parse error:', err);
                throw new Error('Invalid JSON response');
            }
            if (!data.success) {
                console.error('Server error:', data.error || 'No success key in response');
                alert('Error: ' + (data.error || 'Unable to like/unlike comment'));
                return;
            }
            const heart = button.querySelector('.heart');
            heart.textContent = data.liked ? '❤️' : '🤍';
            heart.classList.toggle('liked', data.liked);
            button.dataset.liked = data.liked ? 'true' : 'false';
            const likeCountElement = button.querySelector('.like-count');
            if (likeCountElement) {
                likeCountElement.textContent = `${data.like_count} likes`;
            }
            console.log('Comment like toggled:', { commentId, liked: data.liked, like_count: data.like_count });
        } catch (err) {
            console.error('AJAX Error:', err);
            alert('Error liking comment. Check console for details.');
        }
    }
});

// See more comments
// document.addEventListener('click', async function (e) {
//     if (e.target.classList.contains('see-more-btn')) {
//         e.preventDefault();
//         const postId = e.target.dataset.postId;
//         const csrfToken = e.target.dataset.csrf;
//         const page = e.target.dataset.page; // Get current page from button
//         try {
//             const response = await fetch(`/posts/${postId}/ajax-comments?page=${page}`, {
//                 method: 'GET',
//                 headers: {
//                     'Content-Type': 'application/json',
//                     'X-Requested-With': 'XMLHttpRequest',
//                     'X-CSRF-TOKEN': csrfToken,
//                     'Accept': 'application/json',
//                 },
//             });
//             console.log('See more response:', {
//                 status: response.status,
//                 ok: response.ok,
//                 headers: [...response.headers],
//             });
//             const text = await response.text();
//             console.log('Raw response:', text);
//             if (!response.ok) {
//                 throw new Error(`HTTP error! Status: ${response.status}`);
//             }
//             let data;
//             try {
//                 data = JSON.parse(text);
//             } catch (err) {
//                 console.error('JSON parse error:', err);
//                 throw new Error('Invalid JSON response');
//             }
//             if (!data.success) {
//                 console.error('Server error:', data.error || 'No success key in response');
//                 alert('Error: ' + (data.error || 'Unable to load comments'));
//                 return;
//             }
//             const commentsList = document.querySelector(`#comments-list-${postId}`);
//             if (commentsList) {
//                 commentsList.innerHTML += data.html; // Append new comments (instead of =)
//             }
//             // Update button for next page
//             e.target.dataset.page = parseInt(page) + 1;
//             if (!data.has_next_page) {
//                 e.target.classList.add('hidden');
//             }
//             const seeLessBtn = document.querySelector(`#comments-toggle-${postId} .see-less-btn`);
//             if (seeLessBtn) {
//                 seeLessBtn.classList.remove('hidden');
//             }
//             // Reattach like button event listeners for new comments
//             document.querySelectorAll('.like-comment-btn').forEach(btn => {
//                 btn.addEventListener('click', async function (e) {
//                     e.preventDefault();
//                     const button = this;
//                     const commentId = button.dataset.commentId;
//                     const csrfToken = button.dataset.csrf;
//                     try {
//                         const response = await fetch(`/comments/${commentId}/like`, {
//                             method: 'POST',
//                             headers: {
//                                 'Content-Type': 'application/json',
//                                 'X-Requested-With': 'XMLHttpRequest',
//                                 'X-CSRF-TOKEN': csrfToken,
//                                 'Accept': 'application/json',
//                             },
//                         });
//                         console.log('Like comment response (dynamic):', {
//                             status: response.status,
//                             ok: response.ok,
//                             headers: [...response.headers],
//                         });
//                         const text = await response.text();
//                         console.log('Raw response (dynamic):', text);
//                         if (!response.ok) {
//                             throw new Error(`HTTP error! Status: ${response.status}`);
//                         }
//                         let data;
//                         try {
//                             data = JSON.parse(text);
//                         } catch (err) {
//                             console.error('JSON parse error:', err);
//                             throw new Error('Invalid JSON response');
//                         }
//                         if (!data.success) {
//                             console.error('Server error:', data.error || 'No success key in response');
//                             alert('Error: ' + (data.error || 'Unable to like/unlike comment'));
//                             return;
//                         }
//                         const heart = button.querySelector('.heart');
//                         heart.textContent = data.liked ? '❤️' : '🤍';
//                         heart.classList.toggle('liked', data.liked);
//                         button.dataset.liked = data.liked ? 'true' : 'false';
//                         const likeCountElement = button.querySelector('.like-count');
//                         if (likeCountElement) {
//                             likeCountElement.textContent = `${data.like_count} likes`;
//                         }
//                         console.log('Comment like toggled (dynamic):', { commentId, liked: data.liked, like_count: data.like_count });
//                     } catch (err) {
//                         console.error('AJAX Error:', err);
//                         alert('Error liking comment. Check console for details.');
//                     }
//                 });
//             });
//         } catch (err) {
//             console.error('AJAX Error:', err);
//             alert('Failed to load comments. Check console for details.');
//         }
//     }
// });

// //handle Edit comment
// function handleEditComment(e) {
//     e.preventDefault();
//     const button = this;
//     const commentId = button.dataset.commentId;
//     const commentDiv = document.querySelector(`#comment-${commentId}`);
//     const textSpan = commentDiv ? commentDiv.querySelector('.comment-text') : null;
//     const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
//     if (!textSpan) {
//         console.error('Comment text element not found for ID:', commentId);
//         alert('Error: Comment text not found. Please refresh the page.');
//         return;
//     }
//     fetch(`/comments/${commentId}`, {
//         method: 'GET',
//         headers: {
//             'X-Requested-With': 'XMLHttpRequest',
//             'X-CSRF-TOKEN': csrfToken,
//             'Accept': 'application/json',
//         },
//     })
//         .then(async response => {
//             console.log('Edit comment response:', {
//                 status: response.status,
//                 ok: response.ok,
//             });
//             if (!response.ok) {
//                 console.log('Raw response:', await response.text());
//                 if (response.status === 401 || response.status === 403) {
//                     throw new Error('Please log in to edit the comment.');
//                 }
//                 throw new Error('Failed to load comment');
//             }
//             const data = await response.json();
//             if (!data.success) {
//                 console.error('Server error:', data.error || 'No success key');
//                 throw new Error(data.error || 'Unable to load comment');
//             }
//             const textarea = document.createElement('textarea');
//             textarea.value = data.comment;
//             textarea.className = 'comment-input w-full p-2 border border-gray-300 rounded';
//             textSpan.replaceWith(textarea);
//             button.textContent = 'Save';
//             button.classList.remove('edit-comment-btn');
//             button.classList.add('save-comment-btn');
//         })
//         .catch(err => {
//             console.error('Edit Error:', err.message);
//             alert('Error loading comment for edit: ' + err.message);
//         });
// }


// // Attach edit comment listeners initially
// document.querySelectorAll('.edit-comment-btn').forEach(btn => {
//     btn.addEventListener('click', handleEditComment);
// });

//Image Crop
document.addEventListener("DOMContentLoaded", function () {
    const imageInput = document.getElementById("image");
    const previewContainer = document.getElementById("preview-container");
    const previewImage = document.getElementById("preview-image");
    const croppedInput = document.getElementById("cropped-image");
    let cropper;

    imageInput.addEventListener("change", function (event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function (e) {
                previewImage.src = e.target.result;
                previewContainer.style.display = "block";

                // Destroy old cropper if exists
                if (cropper) {
                    cropper.destroy();
                }

                // Initialize cropper
                cropper = new Cropper(previewImage, {
                    aspectRatio: 1, 
                    viewMode: 1,
                    autoCropArea: 1,
                });
            };
            reader.readAsDataURL(file);
        } else {
            previewContainer.style.display = "none";
        }
    });

    // Before form submit -> get cropped image
    document.querySelector("form.post-form").addEventListener("submit", function (e) {
        if (cropper) {
            const canvas = cropper.getCroppedCanvas({
                width: 1080,
                height: 1080,
            });
            croppedInput.value = canvas.toDataURL("image/jpeg"); // save as base64
        }
    });
});


// Dlete post

document.addEventListener("DOMContentLoaded", () => {
    const menus = document.querySelectorAll(".menu-container");

    menus.forEach(menu => {
        const btn = menu.querySelector(".menu-btn");
        const dropdown = menu.querySelector(".menu-dropdown");

        btn.addEventListener("click", (e) => {
            e.stopPropagation();
            dropdown.classList.toggle("hidden");
        });

        // Close when clicking outside
        document.addEventListener("click", () => {
            dropdown.classList.add("hidden");
        });
    });
});

document.addEventListener("DOMContentLoaded", () => {
    let deletedPost = null;
    let undoTimeout;
    let deleteUrl = null;
    const modal = document.getElementById("deleteModal");
    const confirmDelete = document.getElementById("confirmDelete");
    const cancelDelete = document.getElementById("cancelDelete");
    const undoToast = document.getElementById("undoToast");
    const undoBtn = document.getElementById("undoBtn");

    // Delete button click → open modal
    document.querySelectorAll(".delete-btn").forEach(btn => {
        btn.addEventListener("click", (e) => {
            deleteUrl = e.target.dataset.url;
            deletedPost = document.getElementById(`post-${e.target.dataset.id}`);
            modal.classList.remove("hidden");
        });
    });

    // Confirm delete
    confirmDelete.addEventListener("click", () => {
        modal.classList.add("hidden");

        // Hide instantly
        deletedPost.style.opacity = "0.5";
        deletedPost.style.pointerEvents = "none";
        undoToast.classList.remove("hidden");

        // Start 3s timer → send AJAX delete if not undone
        undoTimeout = setTimeout(() => {
            fetch(deleteUrl, {
                method: "DELETE",
                headers: {
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                    "Accept": "application/json"
                }
            })
                .then(res => {
                    if (!res.ok) throw new Error("Delete failed");
                    deletedPost.remove();
                    undoToast.classList.add("hidden");
                })
                .catch(err => {
                    console.error(err);
                    // Restore if error
                    deletedPost.style.opacity = "1";
                    deletedPost.style.pointerEvents = "auto";
                    undoToast.classList.add("hidden");
                });
            // Starts timer
        }, 3500);
    });

    // Cancel modal
    cancelDelete.addEventListener("click", () => {
        modal.classList.add("hidden");
        deletedPost = null;
        deleteUrl = null;
    });

    // Undo delete
    undoBtn.addEventListener("click", () => {
        clearTimeout(undoTimeout);
        undoToast.classList.add("hidden");
        if (deletedPost) {
            deletedPost.style.opacity = "1";
            deletedPost.style.pointerEvents = "auto";
        }
        deletedPost = null;
        deleteUrl = null;
    });
});
