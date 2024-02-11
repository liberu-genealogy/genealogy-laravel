import preset from "./vendor/filament/support/tailwind.config.preset";

module.exports = {
    presets: [preset],
    content: [
        "./app/Filament/**/*.php",
        "./resources/views/**/*.blade.php",
        "./vendor/filament/**/*.blade.php",
    ],
};
