CREATE DEFINER=`mariadb`@`%` EVENT `purge_expired_secrets` ON SCHEDULE EVERY 5 MINUTE STARTS '2023-05-11 21:43:37' ON COMPLETION PRESERVE ENABLE DO DELETE secrets, secret_expirations
FROM secrets
	INNER JOIN secret_expirations ON 1=1
    	AND secrets.id=secret_expirations.secret_id
        WHERE secret_expirations.expires_at < NOW()
        	OR secret_expirations.remaining_views <= 0
