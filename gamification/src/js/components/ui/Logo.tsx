import { CDN_BASE } from "../../Constants";

export default function Logo() {
	return (
		<div className="logo">
			<img src={`${CDN_BASE}/XR-logo+(1).png`} alt="XR Server Logo" />
		</div>
	)
}