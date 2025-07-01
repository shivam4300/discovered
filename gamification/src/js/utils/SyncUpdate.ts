class SyncedUpdate {
	loop: number;

	previousDelta: number;

	fps: number;

	callback: (delta:number) => void;

	constructor(targetFps:number, callback:()=>void) {
		this.loop = 0;
		this.previousDelta = 0;
		this.fps = targetFps;
		this.callback = callback;
		
		if (!this.callback) {
			console.error('No callback set for SyncedUpdate');
		}
	}

	start = () => {
		if (!this.loop) {
			this.loop = requestAnimationFrame(this.update);
		}
	};

	update = (currentDelta:number) => {
		this.loop = requestAnimationFrame(this.update);

		const delta = currentDelta - this.previousDelta;

		if (this.fps && delta < 1000 / this.fps) {
			return;
		}

		if (this.callback) {
			this.callback(currentDelta);
		}

		this.previousDelta = currentDelta;
	};

	stop = () => {
		cancelAnimationFrame(this.loop);
		this.loop = 0;
	};
}

export default SyncedUpdate;
