INSERT INTO public.users (login, role_id, hash) VALUES (:login, 1, :hash) WHERE NOT EXISTS (SELECT * FROM public.users);