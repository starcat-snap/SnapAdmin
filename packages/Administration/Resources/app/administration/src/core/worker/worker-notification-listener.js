/**
 * @package admin
 */

const { Application, WorkerNotification } = SnapAdmin;

// eslint-disable-next-line sw-deprecation-rules/private-feature-declarations
export const POLL_BACKGROUND_INTERVAL = 30000;

// eslint-disable-next-line sw-deprecation-rules/private-feature-declarations
export const POLL_FOREGROUND_INTERVAL = 5000;

class WorkerNotificationListener {
    constructor(context) {
        this._context = context;
        this._isRunning = false;
        this._isRequestRunning = false;
        this._interval = POLL_BACKGROUND_INTERVAL;
        this._isIntervalWatcherSetup = false;
        this._timeoutId = null;
        this._applicationRoot = null;
        this._middlewareHelper = null;
    }

    start() {
        if (!SnapAdmin.Context.app.config.adminWorker.enableQueueStatsWorker) {
            return;
        }

        this._isRunning = true;
        this._middlewareHelper = WorkerNotification.initialize();
        this._timeoutId = setTimeout(this._checkQueue.bind(this), this._interval);
        this.setupIntervalWatcher();
    }

    terminate() {
        this._isRunning = false;
        if (this._timeoutId !== null) {
            clearTimeout(this._timeoutId);
            this._timeoutId = null;
        }
    }

    setupIntervalWatcher() {
        if (this._isIntervalWatcherSetup) {
            return;
        }

        SnapAdmin.State.watch((state) => {
            return state.notification.workerProcessPollInterval;
        }, this._onPollIntervalChanged.bind(this));

        this._isIntervalWatcherSetup = true;
    }

    _checkQueue() {
        this._isRequestRunning = true;
        const client = SnapAdmin.Application.getContainer('init').httpClient;
        const headers = {
            headers: {
                Authorization: `Bearer ${SnapAdmin.Service('loginService').getToken()}`,
            },
        };

        client.get('/_info/queue.json', headers).then((res) => {
            this._isRequestRunning = false;
            this._timeoutId = null;

            // Notify the worker notification middleware
            this.runNotificationMiddleware(res.data);

            if (this._isRunning) {
                this._interval = SnapAdmin.State.get('notification').workerProcessPollInterval;
                this._timeoutId = setTimeout(this._checkQueue.bind(this), this._interval);
            }
        });
    }

    _onPollIntervalChanged(newInterval) {
        this._interval = newInterval;
        if (this._isRequestRunning) {
            return;
        }

        if (this._timeoutId !== null) {
            clearTimeout(this._timeoutId);
            this._timeoutId = null;
        }

        if (newInterval === POLL_FOREGROUND_INTERVAL) {
            this._checkQueue();
            return;
        }

        this._timeoutId = setTimeout(this._checkQueue.bind(this), this._interval);
    }

    runNotificationMiddleware(response) {
        const appRoot = this._getApplicationRootReference();
        const queue = response;

        const middlewareParams = {
            $root: appRoot,
            notification: {
                create: (notification) => {
                    return SnapAdmin.State.dispatch(
                        'notification/createNotification',
                        notification,
                    );
                },
                update: (notification) => {
                    return SnapAdmin.State.dispatch(
                        'notification/updateNotification',
                        notification,
                    );
                },
            },
            queue,
        };

        this._middlewareHelper.go(middlewareParams);
    }

    _getApplicationRootReference() {
        if (!this._applicationRoot) {
            this._applicationRoot = Application.getApplicationRoot();
        }

        return this._applicationRoot;
    }
}

/**
 * @private
 */
export default WorkerNotificationListener;
