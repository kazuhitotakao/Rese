window.addEventListener('DOMContentLoaded', function () {
    const inputDate = document.getElementById('inputDate');
    const tableDate = document.getElementById('tableDate');
    const tableTime = document.getElementById('tableTime');
    const tableNumber = document.getElementById('tableNumber');

    tableDate.textContent = inputDate.value;
    tableTime.textContent = $("#selectTime option:selected").data("time");
    tableNumber.textContent = $("#selectNumber option:selected").data("number");

    inputDate.addEventListener('change', function () {
        tableDate.textContent = inputDate.value;
    });
    selectTime.addEventListener('change', function () {
        tableTime.textContent = $("#selectTime option:selected").data("time");
    });
    selectNumber.addEventListener('change', function () {
        tableNumber.textContent = $("#selectNumber option:selected").data("number");
    });
});

const button = document.getElementById('reservationButton');
if (flgBtn) {
    button.textContent = '変更する';
} else {
    button.textContent = '予約する';
}
