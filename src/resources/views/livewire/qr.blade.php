<div>
    <div id="loading">ブラウザのカメラの使用を許可してください。</div>
    @if (session()->has('message'))
    <div class="alert alert-success d-flex align-items-center" role="alert">
        <i class="fa-solid fa-circle-check mr-1"></i>
        {{ session('message') }}
    </div>
    @endif
    <canvas id="canvas" hidden></canvas>

    <style>
        #canvas {
            width: 100%;
            height: 720px;
        }
    </style>
    {{-- @pushで囲んだ内容を@stackで出力する --}}
    @push('scripts')

    <script>
        const video = document.createElement('video');
        const canvasElement = document.getElementById('canvas');
        const canvas = canvasElement.getContext('2d');
        const loading = document.getElementById('loading');
        const output = document.getElementById('output');
        let previousData = '';

        navigator.mediaDevices.getUserMedia({
                video: {
                    facingMode: 'environment',
                }
            })
            .then((stream) => {
                video.srcObject = stream;
                video.setAttribute('playsinline', true);
                video.play();
                requestAnimationFrame(tick);
            });

        function tick() {
            loading.innerText = 'ロード中...';
            if (video.readyState === video.HAVE_ENOUGH_DATA) {
                loading.hidden = true;
                canvasElement.hidden = false;
                canvasElement.height = video.videoHeight;
                canvasElement.width = video.videoWidth;
                canvas.drawImage(video, 0, 0, canvasElement.width, canvasElement.height);
                var imageData = canvas.getImageData(0, 0, canvasElement.width, canvasElement.height);
                var code = jsQR(imageData.data, imageData.width, imageData.height, {
                    inversionAttempts: 'dontInvert',
                });
                // 直前に読み込んだQRコードの会員ならスキップさせる。
                if (code && code.data !== previousData) {
                    previousData = code.data; // いま読み込んだデータをチェックに使うために変数に退避しておく
                    @this.check(code.data) // livewireのメソッドを実行する。引数に予約IDが入る
                }
            }
            requestAnimationFrame(tick);
        }
    </script>
    @endpush

</div>