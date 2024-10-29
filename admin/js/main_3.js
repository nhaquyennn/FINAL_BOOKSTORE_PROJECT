document.addEventListener('DOMContentLoaded', function() {
    const phoneNumberInput = document.getElementById('txt_phone');
    const errorMessage = document.getElementById('errorMessage');
    const signup_btn = document.getElementById('signup_btn');

    phoneNumberInput.addEventListener('input', function() {
        const regex = /^0\d{9}$/;
        if (regex.test(phoneNumberInput.value)) {
            errorMessage.style.display = 'none';
            signup_btn.disabled = false;
        } else {
            errorMessage.style.display = 'block';
            signup_btn.disabled = true; // Disable the submit button if the phone number is invalid
        }
    });

    document.getElementById('registrationForm').addEventListener('submit', function(event) {
        if (signup_btn.disabled) {
            event.preventDefault(); // Ngăn chặn gửi form nếu số điện thoại không hợp lệ
            console.log('Form submission prevented');
        }
        // Không hiện modal ở đây
    });
});
