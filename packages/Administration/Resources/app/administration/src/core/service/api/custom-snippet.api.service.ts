import type { AxiosInstance, AxiosResponse } from 'axios';
import type { LoginService } from '../login.service';
import ApiService from '../api.service';

/**
 * @package checkout
 */
// eslint-disable-next-line sw-deprecation-rules/private-feature-declarations
export default class CustomSnippetApiService extends ApiService {
    constructor(httpClient: AxiosInstance, loginService: LoginService) {
        super(httpClient, loginService, 'custom-snippet', 'application/json');

        this.name = 'customSnippetApiService';
    }

    snippets(): Promise<unknown> {
        return this.httpClient
            .get(`/_action/${this.getApiBasePath()}`, {
                headers: this.getBasicHeaders(),
            }).then((response: AxiosResponse<Array<string[]>>) => {
                return ApiService.handleResponse(response);
            });
    }
}
