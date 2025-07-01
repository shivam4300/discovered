/**
 * Linear interpolation between 2 numbers
 */
export const lerp = function lerp(v0:number, v1:number, t:number) {
	return v0 * (1 - t) + v1 * t;
};
