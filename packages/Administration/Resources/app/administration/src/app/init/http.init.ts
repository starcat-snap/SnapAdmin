/**
 * @package admin
 */
import type { AxiosInstance } from 'axios';

const HttpClient = SnapAdmin.Classes._private.HttpFactory;

// eslint-disable-next-line sw-deprecation-rules/private-feature-declarations
export default function initializeHttpClient(): AxiosInstance {
    return HttpClient(SnapAdmin.Context.api) as unknown as AxiosInstance;
}
