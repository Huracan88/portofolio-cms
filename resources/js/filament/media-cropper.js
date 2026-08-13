import Cropper from 'cropperjs';

window.mediaCropper = (config) => ({
    mediaId: config.mediaId,
    presets: config.presets,
    quality: config.defaultQuality,
    maxWidth: config.defaultMaxWidth,
    asWebp: true,
    mode: 'overwrite',
    overwrite: true,
    activePreset: 'free',
    isApplying: false,
    cropper: null,
    initAttempts: 0,

    init() {
        this.$nextTick(() => this.waitForImage());
    },

    waitForImage() {
        const image = this.$refs.sourceImage;

        if (!image) {
            if (this.initAttempts >= 100) {
                return;
            }

            this.initAttempts += 1;

            setTimeout(() => this.waitForImage(), 50);

            return;
        }

        if (image.complete && image.naturalWidth > 0 && image.offsetWidth > 0) {
            this.initCropper(image);

            return;
        }

        if (this.initAttempts >= 100) {
            return;
        }

        this.initAttempts += 1;

        setTimeout(() => this.waitForImage(), 50);
    },

    initCropper(image) {
        if (this.cropper) {
            return;
        }

        this.cropper = new Cropper(image, {
            viewMode: 1,
            dragMode: 'crop',
            autoCropArea: 1,
            responsive: true,
            restore: false,
            checkOrientation: true,
            modal: true,
            guides: true,
            center: true,
            highlight: false,
            background: false,
        });
    },

    setPreset(name) {
        const preset = this.presets.find((item) => item.name === name);

        if (!preset || !this.cropper) {
            return;
        }

        this.activePreset = name;

        if (preset.ratio) {
            this.cropper.setAspectRatio(preset.ratio);
        } else {
            this.cropper.setAspectRatio(NaN);
        }
    },

    apply() {
        if (!this.cropper || this.isApplying) {
            return;
        }

        const data = this.cropper.getData();

        this.isApplying = true;

        this.$wire.applyCrop(this.mediaId, {
            mode: this.mode,
            preset: this.activePreset,
            crop: {
                x: Math.round(data.x),
                y: Math.round(data.y),
                width: Math.round(data.width),
                height: Math.round(data.height),
            },
            maxWidth: parseInt(this.maxWidth, 10) || config.defaultMaxWidth,
            quality: parseInt(this.quality, 10) || config.defaultQuality,
            asWebp: this.asWebp,
        }).finally(() => {
            this.isApplying = false;
        });
    },

    destroy() {
        if (this.cropper) {
            this.cropper.destroy();
            this.cropper = null;
        }
    },
});
