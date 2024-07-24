--
-- PostgreSQL database dump
--

-- Dumped from database version 16.3
-- Dumped by pg_dump version 16.3

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

--
-- Name: public; Type: SCHEMA; Schema: -; Owner: -
--

-- *not* creating schema, since initdb creates it


--
-- Name: stats; Type: SCHEMA; Schema: -; Owner: -
--

CREATE SCHEMA stats;


SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- Name: academic_degrees; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.academic_degrees (
    id bigint NOT NULL,
    title text NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: academic_degrees_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.academic_degrees_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: academic_degrees_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.academic_degrees_id_seq OWNED BY public.academic_degrees.id;


--
-- Name: accessables; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.accessables (
    acl_id bigint NOT NULL,
    accessable_id bigint NOT NULL,
    accessable_type character varying(255) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: acls; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.acls (
    id bigint NOT NULL,
    name text NOT NULL,
    description text NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: acls_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.acls_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: acls_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.acls_id_seq OWNED BY public.acls.id;


--
-- Name: activities; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.activities (
    id bigint NOT NULL,
    user_id bigint,
    content_type text,
    object_id bigint,
    action_flag integer,
    change_message text,
    user_real_name text,
    changes text,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: activities_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.activities_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: activities_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.activities_id_seq OWNED BY public.activities.id;


--
-- Name: articles; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.articles (
    id bigint NOT NULL,
    title_en text NOT NULL,
    content_en text,
    title_de text NOT NULL,
    content_de text,
    slug character varying(255) NOT NULL,
    is_published boolean DEFAULT false NOT NULL,
    created_from text,
    updated_from text,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: articles_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.articles_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: articles_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.articles_id_seq OWNED BY public.articles.id;


--
-- Name: assetables; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.assetables (
    asset_id bigint NOT NULL,
    assetable_id bigint NOT NULL,
    assetable_type character varying(255) NOT NULL,
    "primary" boolean DEFAULT true NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: assets; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.assets (
    id bigint NOT NULL,
    original_file_name character varying(255) NOT NULL,
    disk character varying(255) NOT NULL,
    path character varying(255) NOT NULL,
    width integer NOT NULL,
    height integer NOT NULL,
    duration integer NOT NULL,
    type smallint,
    guid uuid NOT NULL,
    player_preview character varying(255),
    converted_for_downloading_at timestamp(0) without time zone,
    converted_for_streaming_at timestamp(0) without time zone,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: assets_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.assets_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: assets_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.assets_id_seq OWNED BY public.assets.id;


--
-- Name: channels; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.channels (
    id bigint NOT NULL,
    url_handle character varying(255) NOT NULL,
    owner_id bigint,
    name character varying(255) NOT NULL,
    description text,
    banner_url character varying(255),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: channels_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.channels_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: channels_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.channels_id_seq OWNED BY public.channels.id;


--
-- Name: chapters; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.chapters (
    id bigint NOT NULL,
    title text,
    "position" bigint DEFAULT '1'::bigint,
    parent_id bigint DEFAULT '0'::bigint,
    series_id bigint NOT NULL,
    "default" boolean DEFAULT false,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: chapters_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.chapters_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: chapters_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.chapters_id_seq OWNED BY public.chapters.id;


--
-- Name: clip_collection; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.clip_collection (
    clip_id bigint NOT NULL,
    collection_id bigint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: clip_tag; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.clip_tag (
    clip_id bigint NOT NULL,
    tag_id bigint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: clips; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.clips (
    id bigint NOT NULL,
    title character varying(255) NOT NULL,
    owner_id bigint,
    semester_id bigint NOT NULL,
    slug character varying(255) NOT NULL,
    description text,
    "posterImage" character varying(255),
    password character varying(255),
    series_id bigint,
    episode integer DEFAULT 1 NOT NULL,
    allow_comments boolean DEFAULT false NOT NULL,
    is_public boolean DEFAULT true NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    organization_id bigint DEFAULT '1'::bigint NOT NULL,
    folder_id character varying(128),
    recording_date date DEFAULT '2024-07-22'::date,
    acronym character varying(10),
    opencast_logo_pos character varying(2) DEFAULT 'TR'::character varying,
    uploaded_at timestamp(0) without time zone,
    is_livestream boolean DEFAULT false,
    language_id bigint DEFAULT '1'::bigint NOT NULL,
    context_id bigint DEFAULT '1'::bigint NOT NULL,
    format_id bigint DEFAULT '1'::bigint NOT NULL,
    type_id bigint DEFAULT '1'::bigint NOT NULL,
    chapter_id bigint,
    supervisor_id bigint,
    image_id integer,
    has_time_availability boolean DEFAULT false NOT NULL,
    time_availability_start timestamp(0) without time zone,
    time_availability_end timestamp(0) without time zone,
    opencast_event_id text
);


--
-- Name: clips_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.clips_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: clips_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.clips_id_seq OWNED BY public.clips.id;


--
-- Name: collections; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.collections (
    id bigint NOT NULL,
    title character varying(255),
    description text,
    "position" integer,
    is_public boolean DEFAULT true NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: collections_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.collections_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: collections_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.collections_id_seq OWNED BY public.collections.id;


--
-- Name: comments; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.comments (
    id bigint NOT NULL,
    owner_id bigint NOT NULL,
    content text NOT NULL,
    type character varying(10) DEFAULT 'backend'::character varying NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    commentable_id integer,
    commentable_type character varying(255)
);


--
-- Name: comments_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.comments_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: comments_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.comments_id_seq OWNED BY public.comments.id;


--
-- Name: contexts; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.contexts (
    id bigint NOT NULL,
    de_name character varying(128),
    en_name character varying(128),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: contexts_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.contexts_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: contexts_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.contexts_id_seq OWNED BY public.contexts.id;


--
-- Name: device_locations; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.device_locations (
    id bigint NOT NULL,
    name character varying(255) NOT NULL,
    description character varying(255) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: device_locations_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.device_locations_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: device_locations_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.device_locations_id_seq OWNED BY public.device_locations.id;


--
-- Name: devices; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.devices (
    id bigint NOT NULL,
    description text,
    comment text,
    has_livestream_func boolean,
    opencast_device_name text,
    name text NOT NULL,
    url text,
    created_from text,
    updated_from text,
    telephone_number text,
    ip_address character varying(255),
    location_id smallint,
    camera_url text,
    power_outlet_url text,
    organization_id bigint DEFAULT '191'::bigint,
    operational boolean DEFAULT false,
    is_hybrid boolean DEFAULT false,
    has_recording_func boolean DEFAULT true,
    room_url text,
    supervisor_id smallint DEFAULT '1'::smallint,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: devices_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.devices_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: devices_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.devices_id_seq OWNED BY public.devices.id;


--
-- Name: documentables; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.documentables (
    document_id bigint NOT NULL,
    documentable_id bigint NOT NULL,
    documentable_type character varying(255) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: documents; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.documents (
    id bigint NOT NULL,
    name character varying(255),
    save_path character varying(255),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: documents_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.documents_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: documents_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.documents_id_seq OWNED BY public.documents.id;


--
-- Name: failed_jobs; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.failed_jobs (
    id bigint NOT NULL,
    uuid character varying(255) NOT NULL,
    connection text NOT NULL,
    queue text NOT NULL,
    payload text NOT NULL,
    exception text NOT NULL,
    failed_at timestamp(0) without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL
);


--
-- Name: failed_jobs_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.failed_jobs_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: failed_jobs_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.failed_jobs_id_seq OWNED BY public.failed_jobs.id;


--
-- Name: formats; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.formats (
    id bigint NOT NULL,
    de_name character varying(128),
    en_name character varying(128),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: formats_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.formats_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: formats_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.formats_id_seq OWNED BY public.formats.id;


--
-- Name: images; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.images (
    id bigint NOT NULL,
    description character varying(255) NOT NULL,
    file_name character varying(255) NOT NULL,
    file_path character varying(255) NOT NULL,
    thumbnail_path character varying(255) NOT NULL,
    mime_type character varying(255),
    file_size character varying(255),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: images_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.images_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: images_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.images_id_seq OWNED BY public.images.id;


--
-- Name: jobs; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.jobs (
    id bigint NOT NULL,
    queue character varying(255) NOT NULL,
    payload text NOT NULL,
    attempts smallint NOT NULL,
    reserved_at integer,
    available_at integer NOT NULL,
    created_at integer NOT NULL
);


--
-- Name: jobs_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.jobs_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: jobs_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.jobs_id_seq OWNED BY public.jobs.id;


--
-- Name: languages; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.languages (
    id bigint NOT NULL,
    code character varying(10),
    name character varying(24),
    long_code character varying(10),
    order_int smallint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: languages_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.languages_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: languages_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.languages_id_seq OWNED BY public.languages.id;


--
-- Name: livestreams; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.livestreams (
    id bigint NOT NULL,
    name text NOT NULL,
    url text,
    content_path text,
    file_path character varying(255),
    active boolean DEFAULT false NOT NULL,
    clip_id bigint,
    app_name character varying(128),
    has_transcoder boolean DEFAULT false NOT NULL,
    time_availability_start timestamp(0) without time zone,
    time_availability_end timestamp(0) without time zone,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    opencast_location_name text
);


--
-- Name: livestreams_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.livestreams_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: livestreams_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.livestreams_id_seq OWNED BY public.livestreams.id;


--
-- Name: migrations; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.migrations (
    id integer NOT NULL,
    migration character varying(255) NOT NULL,
    batch integer NOT NULL
);


--
-- Name: migrations_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.migrations_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: migrations_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.migrations_id_seq OWNED BY public.migrations.id;


--
-- Name: notifications; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.notifications (
    id uuid NOT NULL,
    type character varying(255) NOT NULL,
    notifiable_type character varying(255) NOT NULL,
    notifiable_id bigint NOT NULL,
    data text NOT NULL,
    read_at timestamp(0) without time zone,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: organizations; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.organizations (
    org_id bigint NOT NULL,
    name character varying(255) NOT NULL,
    parent_org_id bigint,
    orgno character varying(255) NOT NULL,
    shortname character varying(255) NOT NULL,
    staff character varying(255),
    startdate date NOT NULL,
    enddate date NOT NULL,
    operationstartdate date NOT NULL,
    operationenddate date NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    slug character varying(255)
);


--
-- Name: organizations_org_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.organizations_org_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: organizations_org_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.organizations_org_id_seq OWNED BY public.organizations.org_id;


--
-- Name: password_resets; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.password_resets (
    email character varying(255) NOT NULL,
    token character varying(255) NOT NULL,
    created_at timestamp(0) without time zone
);


--
-- Name: podcast_episodes; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.podcast_episodes (
    id bigint NOT NULL,
    episode_number integer NOT NULL,
    recording_date date DEFAULT '2024-07-22'::date,
    title character varying(255) NOT NULL,
    slug character varying(255) NOT NULL,
    podcast_id bigint NOT NULL,
    description text,
    notes text,
    transcription text,
    image_id integer,
    is_published boolean DEFAULT true NOT NULL,
    website_url character varying(255),
    spotify_url character varying(255),
    apple_podcasts_url character varying(255),
    old_episode_id bigint,
    published_at timestamp(0) without time zone,
    folder_id character varying(128),
    owner_id bigint,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: podcast_episodes_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.podcast_episodes_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: podcast_episodes_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.podcast_episodes_id_seq OWNED BY public.podcast_episodes.id;


--
-- Name: podcasts; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.podcasts (
    id bigint NOT NULL,
    title character varying(255) NOT NULL,
    slug character varying(255) NOT NULL,
    description text,
    image_id integer,
    is_published boolean DEFAULT true NOT NULL,
    website_url character varying(255),
    spotify_url character varying(255),
    apple_podcasts_url character varying(255),
    old_podcast_id bigint,
    owner_id bigint,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: podcasts_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.podcasts_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: podcasts_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.podcasts_id_seq OWNED BY public.podcasts.id;


--
-- Name: presentables; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.presentables (
    presenter_id bigint NOT NULL,
    presentable_id bigint NOT NULL,
    presentable_type character varying(255) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: presenters; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.presenters (
    id bigint NOT NULL,
    academic_degree_id bigint,
    first_name character varying(255) NOT NULL,
    last_name character varying(255) NOT NULL,
    username character varying(255),
    email character varying(255),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    image_id integer,
    slug character varying(255)
);


--
-- Name: presenters_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.presenters_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: presenters_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.presenters_id_seq OWNED BY public.presenters.id;


--
-- Name: role_user; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.role_user (
    user_id bigint NOT NULL,
    role_id bigint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: roles; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.roles (
    id bigint NOT NULL,
    name character varying(255) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: roles_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.roles_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: roles_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.roles_id_seq OWNED BY public.roles.id;


--
-- Name: saml2_tenants; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.saml2_tenants (
    id integer NOT NULL,
    uuid uuid NOT NULL,
    key character varying(255),
    idp_entity_id character varying(255) NOT NULL,
    idp_login_url character varying(255) NOT NULL,
    idp_logout_url character varying(255) NOT NULL,
    idp_x509_cert text NOT NULL,
    metadata json NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    deleted_at timestamp(0) without time zone,
    relay_state_url character varying(255),
    name_id_format character varying(255) DEFAULT 'persistent'::character varying NOT NULL
);


--
-- Name: saml2_tenants_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.saml2_tenants_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: saml2_tenants_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.saml2_tenants_id_seq OWNED BY public.saml2_tenants.id;


--
-- Name: semesters; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.semesters (
    id bigint NOT NULL,
    name character varying(128) NOT NULL,
    acronym character varying(10) NOT NULL,
    short_title character varying(128) NOT NULL,
    start_date timestamp(0) without time zone NOT NULL,
    stop_date timestamp(0) without time zone NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: semesters_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.semesters_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: semesters_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.semesters_id_seq OWNED BY public.semesters.id;


--
-- Name: series; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.series (
    id bigint NOT NULL,
    title character varying(255) NOT NULL,
    owner_id bigint,
    slug character varying(255) NOT NULL,
    description text,
    opencast_series_id text,
    password character varying(255),
    is_public boolean DEFAULT true NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    organization_id bigint DEFAULT '1'::bigint NOT NULL,
    lms_link character varying(255),
    opencast_logo_pos character varying(2) DEFAULT 'TR'::character varying,
    ls_auto_reservation boolean DEFAULT true,
    ls_reservation_layout text DEFAULT 'sbs'::text,
    image_id integer
);


--
-- Name: series_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.series_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: series_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.series_id_seq OWNED BY public.series.id;


--
-- Name: series_members; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.series_members (
    series_id bigint NOT NULL,
    user_id bigint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: series_subscriptions; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.series_subscriptions (
    user_id bigint NOT NULL,
    series_id bigint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: settings; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.settings (
    id bigint NOT NULL,
    name character varying(255) NOT NULL,
    data json,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: settings_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.settings_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: settings_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.settings_id_seq OWNED BY public.settings.id;


--
-- Name: tags; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.tags (
    id bigint NOT NULL,
    name character varying(255) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: tags_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.tags_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: tags_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.tags_id_seq OWNED BY public.tags.id;


--
-- Name: trix_attachments; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.trix_attachments (
    id bigint NOT NULL,
    field character varying(255) NOT NULL,
    attachable_id integer,
    attachable_type character varying(255) NOT NULL,
    attachment character varying(255) NOT NULL,
    disk character varying(255) NOT NULL,
    is_pending boolean DEFAULT true NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: trix_attachments_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.trix_attachments_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: trix_attachments_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.trix_attachments_id_seq OWNED BY public.trix_attachments.id;


--
-- Name: trix_rich_texts; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.trix_rich_texts (
    id bigint NOT NULL,
    field character varying(255) NOT NULL,
    model_type character varying(255) NOT NULL,
    model_id bigint NOT NULL,
    content text,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: trix_rich_texts_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.trix_rich_texts_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: trix_rich_texts_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.trix_rich_texts_id_seq OWNED BY public.trix_rich_texts.id;


--
-- Name: types; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.types (
    id bigint NOT NULL,
    de_name character varying(128),
    en_name character varying(128),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: types_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.types_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: types_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.types_id_seq OWNED BY public.types.id;


--
-- Name: users; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.users (
    id bigint NOT NULL,
    first_name character varying(255) NOT NULL,
    last_name character varying(255) NOT NULL,
    username character varying(255) NOT NULL,
    email character varying(255) NOT NULL,
    email_verified_at timestamp(0) without time zone,
    password character varying(255) NOT NULL,
    remember_token character varying(100),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    login_type character varying(10) DEFAULT 'websso'::character varying NOT NULL,
    saml_role character varying(255) DEFAULT 'affiliate'::character varying NOT NULL,
    saml_entitlement smallint DEFAULT '0'::smallint NOT NULL,
    presenter_id character varying(255)
);


--
-- Name: users_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.users_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: users_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.users_id_seq OWNED BY public.users.id;


--
-- Name: botname; Type: TABLE; Schema: stats; Owner: -
--

CREATE TABLE stats.botname (
    botname_id bigint NOT NULL,
    name character varying(255) NOT NULL
);


--
-- Name: botname_seq; Type: SEQUENCE; Schema: stats; Owner: -
--

CREATE SEQUENCE stats.botname_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: clip; Type: TABLE; Schema: stats; Owner: -
--

CREATE TABLE stats.clip (
    clip_id bigint NOT NULL,
    course_id bigint
);


--
-- Name: course; Type: TABLE; Schema: stats; Owner: -
--

CREATE TABLE stats.course (
    course_id bigint NOT NULL
);


--
-- Name: geoloc; Type: TABLE; Schema: stats; Owner: -
--

CREATE TABLE stats.geoloc (
    geoloc_id bigint NOT NULL,
    version bigint NOT NULL,
    bavaria bigint NOT NULL,
    germany bigint NOT NULL,
    month timestamp without time zone NOT NULL,
    resourceid bigint NOT NULL,
    world bigint NOT NULL
);


--
-- Name: geoloc_id_seq; Type: SEQUENCE; Schema: stats; Owner: -
--

CREATE SEQUENCE stats.geoloc_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: hourstats; Type: TABLE; Schema: stats; Owner: -
--

CREATE TABLE stats.hourstats (
    hour bigint NOT NULL,
    counter bigint NOT NULL,
    version bigint
);


--
-- Name: TABLE hourstats; Type: COMMENT; Schema: stats; Owner: -
--

COMMENT ON TABLE stats.hourstats IS 'Kann eigentlich weg, muss aber in vpmig entfernt werden';


--
-- Name: log_seq; Type: SEQUENCE; Schema: stats; Owner: -
--

CREATE SEQUENCE stats.log_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: logs; Type: TABLE; Schema: stats; Owner: -
--

CREATE TABLE stats.logs (
    log_id bigint DEFAULT nextval('stats.log_seq'::regclass) NOT NULL,
    resource_id bigint DEFAULT 0 NOT NULL,
    service_id smallint DEFAULT 0 NOT NULL,
    access_date date DEFAULT now(),
    access_time timestamp without time zone DEFAULT now(),
    remote_addr character varying(255),
    remote_host text,
    remote_user text,
    script_name text,
    is_counted boolean DEFAULT true NOT NULL,
    created_at timestamp without time zone DEFAULT now(),
    created_from text,
    is_valid boolean DEFAULT false NOT NULL,
    in_range boolean DEFAULT false NOT NULL,
    referer text,
    query text,
    is_akami boolean DEFAULT false NOT NULL,
    server character varying(10),
    range character varying(128),
    response character varying(128),
    real_ip character varying(128),
    num_ip bigint,
    last_modified_at timestamp without time zone DEFAULT now(),
    last_modified_from character varying(18),
    bot_name character varying(255),
    city character varying(255),
    country character varying(255),
    country3 character varying(255),
    country_name character varying(255),
    is_bot boolean DEFAULT false,
    is_get boolean DEFAULT true,
    region character varying(255),
    region_name character varying(255)
);


--
-- Name: lastmonth_hour_stats; Type: VIEW; Schema: stats; Owner: -
--

CREATE VIEW stats.lastmonth_hour_stats AS
 SELECT date_part('hour'::text, access_time) AS stunde,
    count(*) AS anz
   FROM stats.logs
  WHERE (access_date > (now() - '1 mon'::interval))
  GROUP BY (date_part('hour'::text, access_time))
  ORDER BY (date_part('hour'::text, access_time));


--
-- Name: lastmonth_weekhour_stats; Type: VIEW; Schema: stats; Owner: -
--

CREATE VIEW stats.lastmonth_weekhour_stats AS
 SELECT date_part('isodow'::text, access_time) AS day,
    date_part('hour'::text, access_time) AS hour,
    count(*) AS value
   FROM stats.logs
  WHERE (access_time > (now() - '1 mon'::interval))
  GROUP BY (date_part('isodow'::text, access_time)), (date_part('hour'::text, access_time))
  ORDER BY (date_part('isodow'::text, access_time)), (date_part('hour'::text, access_time));


--
-- Name: lastweek_hour_stats; Type: VIEW; Schema: stats; Owner: -
--

CREATE VIEW stats.lastweek_hour_stats AS
 SELECT date_part('hour'::text, access_time) AS stunde,
    count(*) AS anz
   FROM stats.logs
  WHERE (access_date > (now() - '7 days'::interval))
  GROUP BY (date_part('hour'::text, access_time))
  ORDER BY (date_part('hour'::text, access_time));


--
-- Name: lastweek_weekhour_stats; Type: VIEW; Schema: stats; Owner: -
--

CREATE VIEW stats.lastweek_weekhour_stats AS
 SELECT date_part('isodow'::text, access_time) AS day,
    date_part('hour'::text, access_time) AS hour,
    count(*) AS value
   FROM stats.logs
  WHERE (access_time > (now() - '7 days'::interval))
  GROUP BY (date_part('isodow'::text, access_time)), (date_part('hour'::text, access_time))
  ORDER BY (date_part('isodow'::text, access_time)), (date_part('hour'::text, access_time));


--
-- Name: month_hours; Type: TABLE; Schema: stats; Owner: -
--

CREATE TABLE stats.month_hours (
    stunde double precision,
    anz bigint
);


--
-- Name: month_weekhours; Type: TABLE; Schema: stats; Owner: -
--

CREATE TABLE stats.month_weekhours (
    day double precision,
    hour double precision,
    value bigint
);


--
-- Name: monthly_requests; Type: TABLE; Schema: stats; Owner: -
--

CREATE TABLE stats.monthly_requests (
    month date NOT NULL,
    counter bigint
);


--
-- Name: resource; Type: TABLE; Schema: stats; Owner: -
--

CREATE TABLE stats.resource (
    resource_id bigint NOT NULL,
    clip_id bigint NOT NULL,
    resolution_id integer
);


--
-- Name: stats; Type: TABLE; Schema: stats; Owner: -
--

CREATE TABLE stats.stats (
    stats_id bigint NOT NULL,
    version bigint NOT NULL,
    counter bigint NOT NULL,
    doa timestamp without time zone NOT NULL,
    resourceid bigint NOT NULL,
    serviceid bigint NOT NULL
);


--
-- Name: monthly_stats_per_resolution; Type: VIEW; Schema: stats; Owner: -
--

CREATE VIEW stats.monthly_stats_per_resolution AS
 SELECT sum,
    doa,
    resolution_id
   FROM ( SELECT sum(s.counter) AS sum,
            date_trunc('month'::text, s.doa) AS doa,
            r.resolution_id
           FROM (stats.stats s
             JOIN stats.resource r ON ((s.resourceid = r.resource_id)))
          WHERE (r.resolution_id <> '-1'::integer)
          GROUP BY (date_trunc('month'::text, s.doa)), r.resolution_id
        UNION ALL
         SELECT sum(s.counter) AS sum,
            date_trunc('month'::text, s.doa) AS doa,
            '-1'::integer AS resolution_id
           FROM (stats.stats s
             JOIN stats.resource r ON ((s.resourceid = r.resource_id)))
          WHERE (r.resolution_id <> '-1'::integer)
          GROUP BY (date_trunc('month'::text, s.doa))) alldata
  ORDER BY doa, resolution_id;


--
-- Name: monthly_stats_per_service; Type: VIEW; Schema: stats; Owner: -
--

CREATE VIEW stats.monthly_stats_per_service AS
 SELECT sum,
    doa,
    serviceid
   FROM ( SELECT sum(stats.counter) AS sum,
            date_trunc('month'::text, stats.doa) AS doa,
            stats.serviceid
           FROM stats.stats
          GROUP BY (date_trunc('month'::text, stats.doa)), stats.serviceid
        UNION ALL
         SELECT sum(stats.counter) AS sum,
            date_trunc('month'::text, stats.doa) AS doa,
            '-1'::integer AS serviceid
           FROM stats.stats
          GROUP BY (date_trunc('month'::text, stats.doa))) alldata
  ORDER BY doa, serviceid;


--
-- Name: monthly_stats_per_service_and_resource; Type: VIEW; Schema: stats; Owner: -
--

CREATE VIEW stats.monthly_stats_per_service_and_resource AS
 SELECT sum,
    doa,
    serviceid,
    resourceid
   FROM ( SELECT sum(stats.counter) AS sum,
            date_trunc('month'::text, stats.doa) AS doa,
            stats.serviceid,
            stats.resourceid
           FROM stats.stats
          GROUP BY stats.resourceid, (date_trunc('month'::text, stats.doa)), stats.serviceid
        UNION ALL
         SELECT sum(stats.counter) AS sum,
            date_trunc('month'::text, stats.doa) AS doa,
            '-1'::integer AS serviceid,
            stats.resourceid
           FROM stats.stats
          GROUP BY stats.resourceid, (date_trunc('month'::text, stats.doa))) alldata
  ORDER BY doa, serviceid;


--
-- Name: stats_seq; Type: SEQUENCE; Schema: stats; Owner: -
--

CREATE SEQUENCE stats.stats_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: week_hours; Type: TABLE; Schema: stats; Owner: -
--

CREATE TABLE stats.week_hours (
    stunde double precision,
    anz bigint
);


--
-- Name: week_weekhours; Type: TABLE; Schema: stats; Owner: -
--

CREATE TABLE stats.week_weekhours (
    day double precision,
    hour double precision,
    value bigint
);


--
-- Name: academic_degrees id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.academic_degrees ALTER COLUMN id SET DEFAULT nextval('public.academic_degrees_id_seq'::regclass);


--
-- Name: acls id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.acls ALTER COLUMN id SET DEFAULT nextval('public.acls_id_seq'::regclass);


--
-- Name: activities id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.activities ALTER COLUMN id SET DEFAULT nextval('public.activities_id_seq'::regclass);


--
-- Name: articles id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.articles ALTER COLUMN id SET DEFAULT nextval('public.articles_id_seq'::regclass);


--
-- Name: assets id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.assets ALTER COLUMN id SET DEFAULT nextval('public.assets_id_seq'::regclass);


--
-- Name: channels id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.channels ALTER COLUMN id SET DEFAULT nextval('public.channels_id_seq'::regclass);


--
-- Name: chapters id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.chapters ALTER COLUMN id SET DEFAULT nextval('public.chapters_id_seq'::regclass);


--
-- Name: clips id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.clips ALTER COLUMN id SET DEFAULT nextval('public.clips_id_seq'::regclass);


--
-- Name: collections id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.collections ALTER COLUMN id SET DEFAULT nextval('public.collections_id_seq'::regclass);


--
-- Name: comments id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.comments ALTER COLUMN id SET DEFAULT nextval('public.comments_id_seq'::regclass);


--
-- Name: contexts id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.contexts ALTER COLUMN id SET DEFAULT nextval('public.contexts_id_seq'::regclass);


--
-- Name: device_locations id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.device_locations ALTER COLUMN id SET DEFAULT nextval('public.device_locations_id_seq'::regclass);


--
-- Name: devices id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.devices ALTER COLUMN id SET DEFAULT nextval('public.devices_id_seq'::regclass);


--
-- Name: documents id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.documents ALTER COLUMN id SET DEFAULT nextval('public.documents_id_seq'::regclass);


--
-- Name: failed_jobs id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.failed_jobs ALTER COLUMN id SET DEFAULT nextval('public.failed_jobs_id_seq'::regclass);


--
-- Name: formats id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.formats ALTER COLUMN id SET DEFAULT nextval('public.formats_id_seq'::regclass);


--
-- Name: images id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.images ALTER COLUMN id SET DEFAULT nextval('public.images_id_seq'::regclass);


--
-- Name: jobs id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.jobs ALTER COLUMN id SET DEFAULT nextval('public.jobs_id_seq'::regclass);


--
-- Name: languages id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.languages ALTER COLUMN id SET DEFAULT nextval('public.languages_id_seq'::regclass);


--
-- Name: livestreams id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.livestreams ALTER COLUMN id SET DEFAULT nextval('public.livestreams_id_seq'::regclass);


--
-- Name: migrations id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.migrations ALTER COLUMN id SET DEFAULT nextval('public.migrations_id_seq'::regclass);


--
-- Name: organizations org_id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.organizations ALTER COLUMN org_id SET DEFAULT nextval('public.organizations_org_id_seq'::regclass);


--
-- Name: podcast_episodes id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.podcast_episodes ALTER COLUMN id SET DEFAULT nextval('public.podcast_episodes_id_seq'::regclass);


--
-- Name: podcasts id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.podcasts ALTER COLUMN id SET DEFAULT nextval('public.podcasts_id_seq'::regclass);


--
-- Name: presenters id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.presenters ALTER COLUMN id SET DEFAULT nextval('public.presenters_id_seq'::regclass);


--
-- Name: roles id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.roles ALTER COLUMN id SET DEFAULT nextval('public.roles_id_seq'::regclass);


--
-- Name: saml2_tenants id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.saml2_tenants ALTER COLUMN id SET DEFAULT nextval('public.saml2_tenants_id_seq'::regclass);


--
-- Name: semesters id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.semesters ALTER COLUMN id SET DEFAULT nextval('public.semesters_id_seq'::regclass);


--
-- Name: series id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.series ALTER COLUMN id SET DEFAULT nextval('public.series_id_seq'::regclass);


--
-- Name: settings id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.settings ALTER COLUMN id SET DEFAULT nextval('public.settings_id_seq'::regclass);


--
-- Name: tags id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.tags ALTER COLUMN id SET DEFAULT nextval('public.tags_id_seq'::regclass);


--
-- Name: trix_attachments id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.trix_attachments ALTER COLUMN id SET DEFAULT nextval('public.trix_attachments_id_seq'::regclass);


--
-- Name: trix_rich_texts id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.trix_rich_texts ALTER COLUMN id SET DEFAULT nextval('public.trix_rich_texts_id_seq'::regclass);


--
-- Name: types id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.types ALTER COLUMN id SET DEFAULT nextval('public.types_id_seq'::regclass);


--
-- Name: users id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.users ALTER COLUMN id SET DEFAULT nextval('public.users_id_seq'::regclass);


--
-- Name: academic_degrees academic_degrees_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.academic_degrees
    ADD CONSTRAINT academic_degrees_pkey PRIMARY KEY (id);


--
-- Name: accessables accessables_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.accessables
    ADD CONSTRAINT accessables_pkey PRIMARY KEY (acl_id, accessable_id, accessable_type);


--
-- Name: acls acls_name_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.acls
    ADD CONSTRAINT acls_name_unique UNIQUE (name);


--
-- Name: acls acls_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.acls
    ADD CONSTRAINT acls_pkey PRIMARY KEY (id);


--
-- Name: activities activities_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.activities
    ADD CONSTRAINT activities_pkey PRIMARY KEY (id);


--
-- Name: articles articles_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.articles
    ADD CONSTRAINT articles_pkey PRIMARY KEY (id);


--
-- Name: articles articles_slug_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.articles
    ADD CONSTRAINT articles_slug_unique UNIQUE (slug);


--
-- Name: assetables assetables_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.assetables
    ADD CONSTRAINT assetables_pkey PRIMARY KEY (asset_id, assetable_id, assetable_type);


--
-- Name: assets assets_guid_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.assets
    ADD CONSTRAINT assets_guid_unique UNIQUE (guid);


--
-- Name: assets assets_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.assets
    ADD CONSTRAINT assets_pkey PRIMARY KEY (id);


--
-- Name: channels channels_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.channels
    ADD CONSTRAINT channels_pkey PRIMARY KEY (id);


--
-- Name: channels channels_url_handle_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.channels
    ADD CONSTRAINT channels_url_handle_unique UNIQUE (url_handle);


--
-- Name: chapters chapters_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.chapters
    ADD CONSTRAINT chapters_pkey PRIMARY KEY (id);


--
-- Name: clip_collection clip_collection_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.clip_collection
    ADD CONSTRAINT clip_collection_pkey PRIMARY KEY (clip_id, collection_id);


--
-- Name: clip_tag clip_tag_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.clip_tag
    ADD CONSTRAINT clip_tag_pkey PRIMARY KEY (clip_id, tag_id);


--
-- Name: clips clips_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.clips
    ADD CONSTRAINT clips_pkey PRIMARY KEY (id);


--
-- Name: clips clips_slug_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.clips
    ADD CONSTRAINT clips_slug_unique UNIQUE (slug);


--
-- Name: collections collections_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.collections
    ADD CONSTRAINT collections_pkey PRIMARY KEY (id);


--
-- Name: comments comments_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.comments
    ADD CONSTRAINT comments_pkey PRIMARY KEY (id);


--
-- Name: contexts contexts_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.contexts
    ADD CONSTRAINT contexts_pkey PRIMARY KEY (id);


--
-- Name: device_locations device_locations_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.device_locations
    ADD CONSTRAINT device_locations_pkey PRIMARY KEY (id);


--
-- Name: devices devices_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.devices
    ADD CONSTRAINT devices_pkey PRIMARY KEY (id);


--
-- Name: documentables documentables_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.documentables
    ADD CONSTRAINT documentables_pkey PRIMARY KEY (document_id, documentable_id, documentable_type);


--
-- Name: documents documents_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.documents
    ADD CONSTRAINT documents_pkey PRIMARY KEY (id);


--
-- Name: failed_jobs failed_jobs_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.failed_jobs
    ADD CONSTRAINT failed_jobs_pkey PRIMARY KEY (id);


--
-- Name: failed_jobs failed_jobs_uuid_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.failed_jobs
    ADD CONSTRAINT failed_jobs_uuid_unique UNIQUE (uuid);


--
-- Name: formats formats_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.formats
    ADD CONSTRAINT formats_pkey PRIMARY KEY (id);


--
-- Name: images images_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.images
    ADD CONSTRAINT images_pkey PRIMARY KEY (id);


--
-- Name: jobs jobs_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.jobs
    ADD CONSTRAINT jobs_pkey PRIMARY KEY (id);


--
-- Name: languages languages_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.languages
    ADD CONSTRAINT languages_pkey PRIMARY KEY (id);


--
-- Name: livestreams livestreams_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.livestreams
    ADD CONSTRAINT livestreams_pkey PRIMARY KEY (id);


--
-- Name: migrations migrations_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.migrations
    ADD CONSTRAINT migrations_pkey PRIMARY KEY (id);


--
-- Name: notifications notifications_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.notifications
    ADD CONSTRAINT notifications_pkey PRIMARY KEY (id);


--
-- Name: organizations organizations_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.organizations
    ADD CONSTRAINT organizations_pkey PRIMARY KEY (org_id);


--
-- Name: organizations organizations_slug_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.organizations
    ADD CONSTRAINT organizations_slug_unique UNIQUE (slug);


--
-- Name: podcast_episodes podcast_episodes_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.podcast_episodes
    ADD CONSTRAINT podcast_episodes_pkey PRIMARY KEY (id);


--
-- Name: podcast_episodes podcast_episodes_slug_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.podcast_episodes
    ADD CONSTRAINT podcast_episodes_slug_unique UNIQUE (slug);


--
-- Name: podcasts podcasts_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.podcasts
    ADD CONSTRAINT podcasts_pkey PRIMARY KEY (id);


--
-- Name: podcasts podcasts_slug_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.podcasts
    ADD CONSTRAINT podcasts_slug_unique UNIQUE (slug);


--
-- Name: presentables presentables_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.presentables
    ADD CONSTRAINT presentables_pkey PRIMARY KEY (presenter_id, presentable_id, presentable_type);


--
-- Name: presenters presenters_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.presenters
    ADD CONSTRAINT presenters_pkey PRIMARY KEY (id);


--
-- Name: presenters presenters_slug_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.presenters
    ADD CONSTRAINT presenters_slug_unique UNIQUE (slug);


--
-- Name: role_user role_user_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.role_user
    ADD CONSTRAINT role_user_pkey PRIMARY KEY (user_id, role_id);


--
-- Name: roles roles_name_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.roles
    ADD CONSTRAINT roles_name_unique UNIQUE (name);


--
-- Name: roles roles_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.roles
    ADD CONSTRAINT roles_pkey PRIMARY KEY (id);


--
-- Name: saml2_tenants saml2_tenants_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.saml2_tenants
    ADD CONSTRAINT saml2_tenants_pkey PRIMARY KEY (id);


--
-- Name: semesters semesters_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.semesters
    ADD CONSTRAINT semesters_pkey PRIMARY KEY (id);


--
-- Name: series_members series_members_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.series_members
    ADD CONSTRAINT series_members_pkey PRIMARY KEY (series_id, user_id);


--
-- Name: series series_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.series
    ADD CONSTRAINT series_pkey PRIMARY KEY (id);


--
-- Name: series series_slug_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.series
    ADD CONSTRAINT series_slug_unique UNIQUE (slug);


--
-- Name: series_subscriptions series_subscriptions_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.series_subscriptions
    ADD CONSTRAINT series_subscriptions_pkey PRIMARY KEY (user_id, series_id);


--
-- Name: settings settings_name_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.settings
    ADD CONSTRAINT settings_name_unique UNIQUE (name);


--
-- Name: settings settings_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.settings
    ADD CONSTRAINT settings_pkey PRIMARY KEY (id);


--
-- Name: tags tags_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.tags
    ADD CONSTRAINT tags_pkey PRIMARY KEY (id);


--
-- Name: trix_attachments trix_attachments_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.trix_attachments
    ADD CONSTRAINT trix_attachments_pkey PRIMARY KEY (id);


--
-- Name: trix_rich_texts trix_rich_texts_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.trix_rich_texts
    ADD CONSTRAINT trix_rich_texts_pkey PRIMARY KEY (id);


--
-- Name: types types_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.types
    ADD CONSTRAINT types_pkey PRIMARY KEY (id);


--
-- Name: users users_email_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_email_unique UNIQUE (email);


--
-- Name: users users_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_pkey PRIMARY KEY (id);


--
-- Name: users users_username_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_username_unique UNIQUE (username);


--
-- Name: botname botname_pkey; Type: CONSTRAINT; Schema: stats; Owner: -
--

ALTER TABLE ONLY stats.botname
    ADD CONSTRAINT botname_pkey PRIMARY KEY (botname_id);


--
-- Name: clip clip_pkey; Type: CONSTRAINT; Schema: stats; Owner: -
--

ALTER TABLE ONLY stats.clip
    ADD CONSTRAINT clip_pkey PRIMARY KEY (clip_id);


--
-- Name: course course_pkey; Type: CONSTRAINT; Schema: stats; Owner: -
--

ALTER TABLE ONLY stats.course
    ADD CONSTRAINT course_pkey PRIMARY KEY (course_id);


--
-- Name: geoloc geoloc_pkey; Type: CONSTRAINT; Schema: stats; Owner: -
--

ALTER TABLE ONLY stats.geoloc
    ADD CONSTRAINT geoloc_pkey PRIMARY KEY (geoloc_id);


--
-- Name: hourstats hourstats_pk; Type: CONSTRAINT; Schema: stats; Owner: -
--

ALTER TABLE ONLY stats.hourstats
    ADD CONSTRAINT hourstats_pk PRIMARY KEY (hour);


--
-- Name: logs logs_pkey; Type: CONSTRAINT; Schema: stats; Owner: -
--

ALTER TABLE ONLY stats.logs
    ADD CONSTRAINT logs_pkey PRIMARY KEY (log_id);


--
-- Name: monthly_requests monthly_requests_pk; Type: CONSTRAINT; Schema: stats; Owner: -
--

ALTER TABLE ONLY stats.monthly_requests
    ADD CONSTRAINT monthly_requests_pk PRIMARY KEY (month);


--
-- Name: resource resource_pkey; Type: CONSTRAINT; Schema: stats; Owner: -
--

ALTER TABLE ONLY stats.resource
    ADD CONSTRAINT resource_pkey PRIMARY KEY (resource_id);


--
-- Name: stats stats_pkey; Type: CONSTRAINT; Schema: stats; Owner: -
--

ALTER TABLE ONLY stats.stats
    ADD CONSTRAINT stats_pkey PRIMARY KEY (stats_id);


--
-- Name: activities_user_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX activities_user_id_index ON public.activities USING btree (user_id);


--
-- Name: assetables_assetable_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX assetables_assetable_id_index ON public.assetables USING btree (assetable_id);


--
-- Name: channels_owner_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX channels_owner_id_index ON public.channels USING btree (owner_id);


--
-- Name: clips_series_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX clips_series_id_index ON public.clips USING btree (series_id);


--
-- Name: jobs_queue_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX jobs_queue_index ON public.jobs USING btree (queue);


--
-- Name: notifications_notifiable_type_notifiable_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX notifications_notifiable_type_notifiable_id_index ON public.notifications USING btree (notifiable_type, notifiable_id);


--
-- Name: password_resets_email_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX password_resets_email_index ON public.password_resets USING btree (email);


--
-- Name: podcast_episodes_podcast_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX podcast_episodes_podcast_id_index ON public.podcast_episodes USING btree (podcast_id);


--
-- Name: series_members_series_id_user_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX series_members_series_id_user_id_index ON public.series_members USING btree (series_id, user_id);


--
-- Name: trix_rich_texts_model_type_model_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX trix_rich_texts_model_type_model_id_index ON public.trix_rich_texts USING btree (model_type, model_id);


--
-- Name: accessDate; Type: INDEX; Schema: stats; Owner: -
--

CREATE INDEX "accessDate" ON stats.logs USING btree (access_date);


--
-- Name: accessTime; Type: INDEX; Schema: stats; Owner: -
--

CREATE INDEX "accessTime" ON stats.logs USING btree (access_time);


--
-- Name: idx_geoloc_resourceid; Type: INDEX; Schema: stats; Owner: -
--

CREATE INDEX idx_geoloc_resourceid ON stats.geoloc USING hash (resourceid);


--
-- Name: idx_res_month; Type: INDEX; Schema: stats; Owner: -
--

CREATE INDEX idx_res_month ON stats.geoloc USING btree (resourceid, month);


--
-- Name: idx_stats_resourceid; Type: INDEX; Schema: stats; Owner: -
--

CREATE INDEX idx_stats_resourceid ON stats.stats USING hash (resourceid);


--
-- Name: monthly_requests_month_uindex; Type: INDEX; Schema: stats; Owner: -
--

CREATE UNIQUE INDEX monthly_requests_month_uindex ON stats.monthly_requests USING btree (month);


--
-- Name: remoteaddr; Type: INDEX; Schema: stats; Owner: -
--

CREATE INDEX remoteaddr ON stats.logs USING btree (remote_addr);


--
-- Name: stats_doa_index; Type: INDEX; Schema: stats; Owner: -
--

CREATE INDEX stats_doa_index ON stats.stats USING btree (doa);


--
-- Name: clips 1; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.clips
    ADD CONSTRAINT "1" FOREIGN KEY (owner_id) REFERENCES public.users(id) ON DELETE SET NULL;


--
-- Name: series 1; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.series
    ADD CONSTRAINT "1" FOREIGN KEY (owner_id) REFERENCES public.users(id) ON DELETE SET NULL;


--
-- Name: comments 1; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.comments
    ADD CONSTRAINT "1" FOREIGN KEY (owner_id) REFERENCES public.users(id) ON DELETE CASCADE;


--
-- Name: chapters 1; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.chapters
    ADD CONSTRAINT "1" FOREIGN KEY (series_id) REFERENCES public.series(id) ON DELETE CASCADE;


--
-- Name: podcasts 1; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.podcasts
    ADD CONSTRAINT "1" FOREIGN KEY (owner_id) REFERENCES public.users(id) ON DELETE SET NULL;


--
-- Name: podcast_episodes 1; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.podcast_episodes
    ADD CONSTRAINT "1" FOREIGN KEY (owner_id) REFERENCES public.users(id) ON DELETE SET NULL;


--
-- Name: assetables 1; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.assetables
    ADD CONSTRAINT "1" FOREIGN KEY (asset_id) REFERENCES public.assets(id) ON DELETE CASCADE;


--
-- Name: accessables accessables_acl_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.accessables
    ADD CONSTRAINT accessables_acl_id_foreign FOREIGN KEY (acl_id) REFERENCES public.acls(id);


--
-- Name: channels channels_owner_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.channels
    ADD CONSTRAINT channels_owner_id_foreign FOREIGN KEY (owner_id) REFERENCES public.users(id) ON DELETE CASCADE;


--
-- Name: clip_collection clip_collection_clip_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.clip_collection
    ADD CONSTRAINT clip_collection_clip_id_foreign FOREIGN KEY (clip_id) REFERENCES public.clips(id) ON DELETE CASCADE;


--
-- Name: clip_collection clip_collection_collection_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.clip_collection
    ADD CONSTRAINT clip_collection_collection_id_foreign FOREIGN KEY (collection_id) REFERENCES public.collections(id) ON DELETE CASCADE;


--
-- Name: clip_tag clip_tag_clip_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.clip_tag
    ADD CONSTRAINT clip_tag_clip_id_foreign FOREIGN KEY (clip_id) REFERENCES public.clips(id) ON DELETE CASCADE;


--
-- Name: clip_tag clip_tag_tag_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.clip_tag
    ADD CONSTRAINT clip_tag_tag_id_foreign FOREIGN KEY (tag_id) REFERENCES public.tags(id) ON DELETE CASCADE;


--
-- Name: clips clips_context_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.clips
    ADD CONSTRAINT clips_context_id_foreign FOREIGN KEY (context_id) REFERENCES public.contexts(id) ON DELETE SET NULL;


--
-- Name: clips clips_format_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.clips
    ADD CONSTRAINT clips_format_id_foreign FOREIGN KEY (format_id) REFERENCES public.formats(id) ON DELETE SET NULL;


--
-- Name: clips clips_language_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.clips
    ADD CONSTRAINT clips_language_id_foreign FOREIGN KEY (language_id) REFERENCES public.languages(id) ON DELETE SET NULL;


--
-- Name: clips clips_organization_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.clips
    ADD CONSTRAINT clips_organization_id_foreign FOREIGN KEY (organization_id) REFERENCES public.organizations(org_id) ON DELETE CASCADE;


--
-- Name: clips clips_semester_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.clips
    ADD CONSTRAINT clips_semester_id_foreign FOREIGN KEY (semester_id) REFERENCES public.semesters(id);


--
-- Name: clips clips_supervisor_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.clips
    ADD CONSTRAINT clips_supervisor_id_foreign FOREIGN KEY (supervisor_id) REFERENCES public.users(id) ON DELETE SET NULL;


--
-- Name: clips clips_type_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.clips
    ADD CONSTRAINT clips_type_id_foreign FOREIGN KEY (type_id) REFERENCES public.types(id) ON DELETE SET NULL;


--
-- Name: documentables documentables_document_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.documentables
    ADD CONSTRAINT documentables_document_id_foreign FOREIGN KEY (document_id) REFERENCES public.documents(id) ON DELETE CASCADE;


--
-- Name: livestreams livestreams_clip_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.livestreams
    ADD CONSTRAINT livestreams_clip_id_foreign FOREIGN KEY (clip_id) REFERENCES public.clips(id) ON DELETE SET NULL;


--
-- Name: presentables presentables_presenter_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.presentables
    ADD CONSTRAINT presentables_presenter_id_foreign FOREIGN KEY (presenter_id) REFERENCES public.presenters(id) ON DELETE CASCADE;


--
-- Name: presenters presenters_academic_degree_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.presenters
    ADD CONSTRAINT presenters_academic_degree_id_foreign FOREIGN KEY (academic_degree_id) REFERENCES public.academic_degrees(id) ON DELETE SET NULL;


--
-- Name: role_user role_user_role_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.role_user
    ADD CONSTRAINT role_user_role_id_foreign FOREIGN KEY (role_id) REFERENCES public.roles(id) ON DELETE CASCADE;


--
-- Name: role_user role_user_user_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.role_user
    ADD CONSTRAINT role_user_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(id) ON DELETE CASCADE;


--
-- Name: series_members series_members_series_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.series_members
    ADD CONSTRAINT series_members_series_id_foreign FOREIGN KEY (series_id) REFERENCES public.series(id) ON DELETE CASCADE;


--
-- Name: series_members series_members_user_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.series_members
    ADD CONSTRAINT series_members_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(id) ON DELETE CASCADE;


--
-- Name: series series_organization_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.series
    ADD CONSTRAINT series_organization_id_foreign FOREIGN KEY (organization_id) REFERENCES public.organizations(org_id) ON DELETE CASCADE;


--
-- Name: series_subscriptions series_subscriptions_series_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.series_subscriptions
    ADD CONSTRAINT series_subscriptions_series_id_foreign FOREIGN KEY (series_id) REFERENCES public.series(id) ON DELETE CASCADE;


--
-- Name: series_subscriptions series_subscriptions_user_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.series_subscriptions
    ADD CONSTRAINT series_subscriptions_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(id) ON DELETE CASCADE;


--
-- Name: clip fk2ea350dafac5f8; Type: FK CONSTRAINT; Schema: stats; Owner: -
--

ALTER TABLE ONLY stats.clip
    ADD CONSTRAINT fk2ea350dafac5f8 FOREIGN KEY (course_id) REFERENCES stats.course(course_id);


--
-- Name: resource fkebabc40eb860ed58; Type: FK CONSTRAINT; Schema: stats; Owner: -
--

ALTER TABLE ONLY stats.resource
    ADD CONSTRAINT fkebabc40eb860ed58 FOREIGN KEY (clip_id) REFERENCES stats.clip(clip_id);


--
-- PostgreSQL database dump complete
--

--
-- PostgreSQL database dump
--

-- Dumped from database version 16.3
-- Dumped by pg_dump version 16.3

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

--
-- Data for Name: migrations; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.migrations (id, migration, batch) FROM stdin;
1	2014_10_12_000000_create_users_table	1
2	2014_10_12_100000_create_password_resets_table	1
3	2019_06_24_140207_create_saml2_tenants_table	1
4	2019_08_19_000000_create_failed_jobs_table	1
5	2020_10_22_140856_add_relay_state_url_column_to_saml2_tenants_table	1
6	2020_10_23_072902_add_name_id_format_column_to_saml2_tenants_table	1
7	2021_02_23_135252_create_semesters_table	1
8	2021_02_24_154008_create_clips_table	1
9	2021_03_01_085453_create_assets_table	1
10	2021_03_23_093038_create_tags_table	1
11	2021_03_23_093435_create_clip_tag_table	1
12	2021_03_30_150851_create_roles_table	1
13	2021_03_30_151210_create_role_user_table	1
14	2021_04_06_101457_create_series_table	1
15	2021_05_18_144105_create_comments_table	1
16	2021_06_15_082854_create_acls_table	1
17	2021_06_15_085735_create_accessables_table	1
18	2021_07_15_094825_create_organizations_table	1
19	2021_07_15_102400_add_clip_organization_unit_id	1
20	2021_07_19_103904_add_series_organization_unit_id	1
21	2021_10_28_091000_create_academic_degrees_table	1
22	2021_12_14_143614_create_presenters_table	1
23	2022_01_10_135539_create_presentables_table	1
24	2022_02_01_123425_add_additional_series_columns	1
25	2022_02_01_124125_add_additional_clips_columns	1
26	2022_02_01_124910_create_languages_table	1
27	2022_02_01_125727_create_contexts_table	1
28	2022_02_01_125735_create_formats_table	1
29	2022_02_01_125743_create_types_table	1
30	2022_02_01_125949_add_clip_relationships	1
31	2022_02_09_112341_create_notifications_table	1
32	2022_03_02_084044_create_activities_table	1
33	2022_03_11_111043_create_chapters_table	1
34	2022_03_21_130512_add_clips_chapter_id	1
35	2022_03_29_122504_create_collections_table	1
36	2022_03_29_141713_create_clip_collection_table	1
37	2022_04_06_144424_create_series_members_table	1
38	2022_04_12_082200_create_devices_table	1
39	2022_04_12_142539_create_device_locations_table	1
40	2022_05_12_093919_create_trix_rich_texts_table	1
41	2022_05_18_084242_create_documents_table	1
42	2022_05_18_131800_create_documentables_table	1
43	2022_09_06_150457_add_users_login_type	1
44	2022_09_08_155621_add_commentables_on_comments	1
45	2022_09_15_111128_add_clip_supervisor	1
46	2022_09_19_102109_create_settings_table	1
47	2022_10_13_160345_create_series_subscriptions_table	1
48	2023_01_10_081831_add_organizations_slug	1
49	2023_01_12_085328_create_images_table	1
50	2023_01_12_095757_add_image_id_to_series	1
51	2023_01_12_100007_add_image_id_to_clips	1
52	2023_03_24_195858_add_image_id_to_presenters	1
53	2023_04_20_150038_add_users_saml_attributes	1
54	2023_07_06_140613_create_livestreams_table	1
55	2023_09_11_132445_add_clips_time_availability	1
56	2023_09_25_113353_create_articles_table	1
57	2023_12_04_144627_add_persenters_slug	1
58	2024_01_10_142742_create_channels_table	1
59	2024_02_07_111845_add_users_presenter_id	1
60	2024_02_20_094838_add_clips_opencast_event_id	1
61	2024_03_20_104014_create_stats_logs_table	1
62	2024_03_20_105115_create_stats_stats_table	1
63	2024_04_08_103929_create_jobs_table	1
64	2024_05_31_092642_add_livestreams_opencast_location_name	1
65	2024_06_21_091852_create_podcasts_table	1
66	2024_06_21_091859_create_podcast_episodes_table	1
67	2024_07_02_145743_create_assetables_table	1
\.


--
-- Name: migrations_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.migrations_id_seq', 67, true);


--
-- PostgreSQL database dump complete
--

