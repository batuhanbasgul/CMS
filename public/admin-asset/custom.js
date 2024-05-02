/**
 * RESIZE FUNCTION
 */
function resizeImage(base64Str, maxWidth, maxHeight) {
    return new Promise((resolve) => {
        let img = new Image()
        img.src = base64Str
        img.onload = () => {
            let canvas = document.createElement('canvas')
            const MAX_WIDTH = maxWidth.value
            const MAX_HEIGHT = maxHeight.value

            // maxWidth.value
            // maxHeight.value
            let width = img.width
            let height = img.height

            if (width > height) {
                if (width > MAX_WIDTH) {
                    height *= MAX_WIDTH / width
                    width = MAX_WIDTH
                }
            } else {
                if (height > MAX_HEIGHT) {
                    width *= MAX_HEIGHT / height
                    height = MAX_HEIGHT
                }
            }

            canvas.width = width
            canvas.height = height
            let ctx = canvas.getContext('2d')
            ctx.drawImage(img, 0, 0, width, height)
            resolve(canvas.toDataURL())
        }
    })
}

/**
 * LARGE SCREEN IMAGES
 */
let original = document.querySelector('.original'),
    img_result = document.querySelector('.img-result'),
    img_w = document.querySelector('.img-w'),
    img_h = document.querySelector('.img-h'),
    ratio = document.querySelector('.ratio'),
    crop = document.querySelector('.crop'),
    cropped = document.querySelector('.cropped'),
    upload = document.querySelector('#image'), //file input upload
    cropper = '';

if (upload) {
    // on change show image with crop options
    upload.addEventListener('change', (e) => {
        //Kontrolleri görünür yap
        document.getElementById('box').classList.remove('d-none');
        if (e.target.files.length) {
            // start file reader
            const reader = new FileReader();
            reader.onload = (e) => {
                if (e.target.result) {
                    // create new image
                    let img = document.createElement('img');
                    img.id = 'js-img-cropper';
                    img.src = e.target.result
                    // clean result before
                    original.innerHTML = '';
                    // append new image
                    original.appendChild(img);
                    //append child attr for scaling cropper
                    original.firstChild.classList.add("img-fluid");
                    Cropper.setDefaults({
                        aspectRatio: ratio.value,    // Ratio
                        zoomable: false,
                        zoomOnTouch: false,
                        zoomOnWheel: false,
                        movable: false,
                        viewMode: 1,
                        checkOrientation: false,
                        autoCropArea: 1
                    });
                    // init cropper
                    cropper = new Cropper(img);
                    document.querySelectorAll('[data-toggle="cropper"]').forEach((e => {
                        e.addEventListener("click", (o => {
                            let a = e.dataset.method || !1,
                                r = e.dataset.option || !1,
                                c = {
                                    zoom: () => {
                                        cropper.zoom(r)
                                    },
                                    setDragMode: () => {
                                        cropper.setDragMode(r)
                                    },
                                    rotate: () => {
                                        cropper.rotate(r)
                                    },
                                    scaleX: () => {
                                        cropper.scaleX(r), e.dataset.option = -r
                                    },
                                    scaleY: () => {
                                        cropper.scaleY(r), e.dataset.option = -r
                                    },
                                    setAspectRatio: () => {
                                        cropper.setAspectRatio(r)
                                    },
                                    crop: () => {
                                        cropper.crop()
                                    },
                                    clear: () => {
                                        cropper.clear()
                                    }
                                };
                            c[a] && c[a]()
                        }))
                    }))
                }
            };
            reader.readAsDataURL(e.target.files[0]);
        }
    });
    // save on click
    crop.addEventListener('click', (e) => {
        e.preventDefault();
        // get result to data uri
        let imgSrc = cropper.getCroppedCanvas({
            //
        }).toDataURL();

        resizeImage(imgSrc, img_w, img_h).then((result) => {
            cropped.src = result;
            document.getElementById("cropped_data").value = result;   //hidden input value ataması, base64 formatında data.
            document.getElementById('box-2').classList.remove('d-none');
        });

    });
}


/**
 * MOBILE SCREEN IMAGES
 */

let original_mobile = document.querySelector('.original-mobile'),
    img_result_mobile = document.querySelector('.img-result-mobile'),
    img_w_mobile = document.querySelector('.img-w-mobile'),
    img_h_mobile = document.querySelector('.img-h-mobile'),
    ratio_mobile = document.querySelector('.ratio-mobile'),
    crop_mobile = document.querySelector('.crop-mobile'),
    cropped_mobile = document.querySelector('.cropped-mobile'),
    upload_mobile = document.querySelector('#image-mobile'), //file input upload
    cropper_mobile = '';

if (upload_mobile) {
    // on change show image with crop options
    upload_mobile.addEventListener('change', (e) => {
        //Kontrolleri görünür yap
        document.getElementById('box-mobile').classList.remove('d-none');
        if (e.target.files.length) {
            // start file reader
            const reader_mobile = new FileReader();
            reader_mobile.onload = (e) => {
                if (e.target.result) {
                    // create new image
                    let img = document.createElement('img');
                    img.id = 'js-img-cropper';
                    img.src = e.target.result
                    // clean result before
                    original_mobile.innerHTML = '';
                    // append new image
                    original_mobile.appendChild(img);
                    //append child attr for scaling cropper
                    original_mobile.firstChild.classList.add("img-fluid");
                    Cropper.setDefaults({
                        aspectRatio: ratio_mobile.value,    // Ratio
                        zoomable: false,
                        zoomOnTouch: false,
                        zoomOnWheel: false,
                        movable: false,
                        viewMode: 1,
                        checkOrientation: false,
                        autoCropArea: 1
                    });
                    // init cropper
                    cropper_mobile = new Cropper(img);
                    document.querySelectorAll('[data-toggle="cropper-mobile"]').forEach((e => {
                        e.addEventListener("click", (o => {
                            let a = e.dataset.method || !1,
                                r = e.dataset.option || !1,
                                c = {
                                    zoom: () => {
                                        cropper_mobile.zoom(r)
                                    },
                                    setDragMode: () => {
                                        cropper_mobile.setDragMode(r)
                                    },
                                    rotate: () => {
                                        cropper_mobile.rotate(r)
                                    },
                                    scaleX: () => {
                                        cropper_mobile.scaleX(r), e.dataset.option = -r
                                    },
                                    scaleY: () => {
                                        cropper_mobile.scaleY(r), e.dataset.option = -r
                                    },
                                    setAspectRatio: () => {
                                        cropper_mobile.setAspectRatio(r)
                                    },
                                    crop: () => {
                                        cropper_mobile.crop()
                                    },
                                    clear: () => {
                                        cropper_mobile.clear()
                                    }
                                };
                            c[a] && c[a]()
                        }))
                    }))
                }
            };
            reader_mobile.readAsDataURL(e.target.files[0]);
        }
    });
    // save on click
    crop_mobile.addEventListener('click', (e) => {
        e.preventDefault();
        // get result to data uri
        let imgSrc = cropper_mobile.getCroppedCanvas({
            //
        }).toDataURL();

        resizeImage(imgSrc, img_w_mobile, img_h_mobile).then((result) => {
            cropped_mobile.src = result;
            document.getElementById("cropped_data_mobile").value = result;   //hidden input value ataması, base64 formatında data.
            document.getElementById('box-2-mobile').classList.remove('d-none');
        });
    });
}


/**
 * FAVICON IMAGES
 */
let original_favicon = document.querySelector('.original-favicon'),
    img_result_favicon = document.querySelector('.img-result-favicon'),
    img_w_favicon = document.querySelector('.img-w-favicon'),
    img_h_favicon = document.querySelector('.img-h-favicon'),
    ratio_favicon = document.querySelector('.ratio-favicon'),
    crop_favicon = document.querySelector('.crop-favicon'),
    cropped_favicon = document.querySelector('.cropped-favicon'),
    upload_favicon = document.querySelector('#image-favicon'), //file input upload
    cropper_favicon = '';

if (upload_favicon) {
    // on change show image with crop options
    upload_favicon.addEventListener('change', (e) => {
        //Kontrolleri görünür yap
        document.getElementById('box-favicon').classList.remove('d-none');
        if (e.target.files.length) {
            // start file reader
            const reader_favicon = new FileReader();
            reader_favicon.onload = (e) => {
                if (e.target.result) {
                    // create new image
                    let img = document.createElement('img');
                    img.id = 'js-img-cropper';
                    img.src = e.target.result
                    // clean result before
                    original_favicon.innerHTML = '';
                    // append new image
                    original_favicon.appendChild(img);
                    //append child attr for scaling cropper
                    original_favicon.firstChild.classList.add("img-fluid");

                    Cropper.setDefaults({
                        aspectRatio: ratio_favicon.value,    // Ratio
                        zoomable: false,
                        zoomOnTouch: false,
                        zoomOnWheel: false,
                        movable: false,
                        viewMode: 1,
                        checkOrientation: false,
                        autoCropArea: 1
                    });
                    // init cropper
                    cropper_favicon = new Cropper(img);
                    document.querySelectorAll('[data-toggle="cropper-favicon"]').forEach((e => {
                        e.addEventListener("click", (o => {
                            let a = e.dataset.method || !1,
                                r = e.dataset.option || !1,
                                c = {
                                    zoom: () => {
                                        cropper_favicon.zoom(r)
                                    },
                                    setDragMode: () => {
                                        cropper_favicon.setDragMode(r)
                                    },
                                    rotate: () => {
                                        cropper_favicon.rotate(r)
                                    },
                                    scaleX: () => {
                                        cropper_favicon.scaleX(r), e.dataset.option = -r
                                    },
                                    scaleY: () => {
                                        cropper_favicon.scaleY(r), e.dataset.option = -r
                                    },
                                    setAspectRatio: () => {
                                        cropper_favicon.setAspectRatio(r)
                                    },
                                    crop: () => {
                                        cropper_favicon.crop()
                                    },
                                    clear: () => {
                                        cropper_favicon.clear()
                                    }
                                };
                            c[a] && c[a]()
                        }))
                    }))
                }
            };
            reader_favicon.readAsDataURL(e.target.files[0]);
        }
    });
    // save on click
    crop_favicon.addEventListener('click', (e) => {
        e.preventDefault();
        // get result to data uri
        let imgSrc = cropper_favicon.getCroppedCanvas({
            //
        }).toDataURL();

        resizeImage(imgSrc, img_w_favicon, img_h_favicon).then((result) => {
            cropped_favicon.src = result;
            document.getElementById("cropped_data_favicon").value = result;   //hidden input value ataması, base64 formatında data.
            document.getElementById('box-2-favicon').classList.remove('d-none');
        });
    });
}


/**
 * BANNER IMAGES
 */
let original_banner = document.querySelector('.original-banner'),
    img_result_banner = document.querySelector('.img-result-banner'),
    img_w_banner = document.querySelector('.img-w-banner'),
    img_h_banner = document.querySelector('.img-h-banner'),
    ratio_banner = document.querySelector('.ratio-banner'),
    crop_banner = document.querySelector('.crop-banner'),
    cropped_banner = document.querySelector('.cropped-banner'),
    upload_banner = document.querySelector('#image-banner'), //file input upload
    cropper_banner = '';

if (upload_banner) {
    // on change show image with crop options
    upload_banner.addEventListener('change', (e) => {
        //Kontrolleri görünür yap
        document.getElementById('box-banner').classList.remove('d-none');
        if (e.target.files.length) {
            // start file reader
            const reader_banner = new FileReader();
            reader_banner.onload = (e) => {
                if (e.target.result) {
                    // create new image
                    let img = document.createElement('img');
                    img.id = 'js-img-cropper';
                    img.src = e.target.result
                    // clean result before
                    original_banner.innerHTML = '';
                    // append new image
                    original_banner.appendChild(img);
                    //append child attr for scaling cropper
                    original_banner.firstChild.classList.add("img-fluid");

                    Cropper.setDefaults({
                        aspectRatio: ratio_banner.value,    // Ratio
                        zoomable: false,
                        zoomOnTouch: false,
                        zoomOnWheel: false,
                        movable: false,
                        viewMode: 1,
                        checkOrientation: false,
                        autoCropArea: 1
                    });
                    // init cropper
                    cropper_banner = new Cropper(img);
                    document.querySelectorAll('[data-toggle="cropper-banner"]').forEach((e => {
                        e.addEventListener("click", (o => {
                            let a = e.dataset.method || !1,
                                r = e.dataset.option || !1,
                                c = {
                                    zoom: () => {
                                        cropper_banner.zoom(r)
                                    },
                                    setDragMode: () => {
                                        cropper_banner.setDragMode(r)
                                    },
                                    rotate: () => {
                                        cropper_banner.rotate(r)
                                    },
                                    scaleX: () => {
                                        cropper_banner.scaleX(r), e.dataset.option = -r
                                    },
                                    scaleY: () => {
                                        cropper_banner.scaleY(r), e.dataset.option = -r
                                    },
                                    setAspectRatio: () => {
                                        cropper_banner.setAspectRatio(r)
                                    },
                                    crop: () => {
                                        cropper_banner.crop()
                                    },
                                    clear: () => {
                                        cropper_banner.clear()
                                    }
                                };
                            c[a] && c[a]()
                        }))
                    }))
                }
            };
            reader_banner.readAsDataURL(e.target.files[0]);
        }
    });
    // save on click
    crop_banner.addEventListener('click', (e) => {
        e.preventDefault();
        // get result to data uri
        let imgSrc = cropper_banner.getCroppedCanvas({
            //
        }).toDataURL();

        resizeImage(imgSrc, img_w_banner, img_h_banner).then((result) => {
            cropped_banner.src = result;
            document.getElementById("cropped_data_banner").value = result;   //hidden input value ataması, base64 formatında data.
            document.getElementById('box-2-banner').classList.remove('d-none');
        });
    });
}



/**
 * MULTIPLE IMAGES
 */
let original_multi = document.querySelector('.original-multi'),
    img_result_multi = document.querySelector('.img-result-multi'),
    img_w_multi = document.querySelector('.img-w-multi'),
    img_h_multi = document.querySelector('.img-h-multi'),
    ratio_multi = document.querySelector('.ratio-multi'),
    crop_multi = document.querySelector('.crop-multi'),
    cropped_multi = document.querySelector('.cropped-multi'),
    upload_multi = document.querySelector('#image-multi'), //file input upload
    cropper_multi = '';

if (upload_multi) {
    // on change show image with crop options
    upload_multi.addEventListener('change', (e) => {
        //Kontrolleri görünür yap
        document.getElementById('box-multi').classList.remove('d-none');
        if (e.target.files.length) {
            // start file reader
            const reader_multi = new FileReader();
            reader_multi.onload = (e) => {
                if (e.target.result) {
                    // create new image
                    let img = document.createElement('img');
                    img.id = 'js-img-cropper';
                    img.src = e.target.result
                    // clean result before
                    original_multi.innerHTML = '';
                    // append new image
                    original_multi.appendChild(img);
                    //append child attr for scaling cropper
                    original_multi.firstChild.classList.add("img-fluid");

                    Cropper.setDefaults({
                        aspectRatio: ratio_multi.value,    // Ratio
                        zoomable: false,
                        zoomOnTouch: false,
                        zoomOnWheel: false,
                        movable: false,
                        viewMode: 1,
                        checkOrientation: false,
                        autoCropArea: 1
                    });
                    // init cropper
                    cropper_multi = new Cropper(img);
                    document.querySelectorAll('[data-toggle="cropper-multi"]').forEach((e => {
                        e.addEventListener("click", (o => {
                            let a = e.dataset.method || !1,
                                r = e.dataset.option || !1,
                                c = {
                                    zoom: () => {
                                        cropper_multi.zoom(r)
                                    },
                                    setDragMode: () => {
                                        cropper_multi.setDragMode(r)
                                    },
                                    rotate: () => {
                                        cropper_multi.rotate(r)
                                    },
                                    scaleX: () => {
                                        cropper_multi.scaleX(r), e.dataset.option = -r
                                    },
                                    scaleY: () => {
                                        cropper_multi.scaleY(r), e.dataset.option = -r
                                    },
                                    setAspectRatio: () => {
                                        cropper_multi.setAspectRatio(r)
                                    },
                                    crop: () => {
                                        cropper_multi.crop()
                                    },
                                    clear: () => {
                                        cropper_multi.clear()
                                    }
                                };
                            c[a] && c[a]()
                        }))
                    }))
                }
            };
            reader_multi.readAsDataURL(e.target.files[0]);
        }
    });
    // save on click
    crop_multi.addEventListener('click', (e) => {
        e.preventDefault();
        // get result to data uri
        let imgSrc = cropper_multi.getCroppedCanvas({
            //
        }).toDataURL();

        resizeImage(imgSrc, img_w_multi, img_h_multi).then((result) => {
            cropped_multi.src = result;
            document.getElementById("cropped_data_multi").value = result;   //hidden input value ataması, base64 formatında data.
            document.getElementById('box-2-multi').classList.remove('d-none');
        });
    });
}
