const profile_Pic = document.getElementById("profile_pic");
const input_File = document.getElementById("photo_input");

input_File.addEventListener("change", () => {
    const file = input_File.files[0];
    if (file) {
        profile_Pic.src = URL.createObjectURL(file);
    }
});
