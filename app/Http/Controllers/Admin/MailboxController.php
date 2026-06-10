<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Webklex\IMAP\Facades\Client;

class MailboxController extends Controller
{
    private const PER_PAGE = 25;

    /**
     * List the latest messages in the INBOX (headers only, read-only).
     */
    public function index(Request $request)
    {
        $page = max(1, (int) $request->query('page', 1));

        try {
            $folder = $this->inbox();

            $messages = $folder->query()
                ->leaveUnread()        // never flip the \Seen flag
                ->setFetchBody(false)  // headers only — fast list
                ->setFetchOrderDesc()  // newest first
                ->limit(self::PER_PAGE, $page)
                ->get();

            $rows = $messages->map(fn ($m) => [
                'uid'      => (int) $m->uid,
                'subject'  => $this->str($m->subject) ?: '(no subject)',
                'from'     => $this->fromLabel($m),
                'date'     => $this->safeDate($m->date),
                'seen'     => $this->isSeen($m),
            ])->values();

            return view('admin.mailbox.index', [
                'rows'     => $rows,
                'page'     => $page,
                'hasNext'  => $rows->count() === self::PER_PAGE,
                'error'    => null,
            ]);
        } catch (\Throwable $e) {
            Log::warning('IMAP inbox failed: '.$e->getMessage());

            return view('admin.mailbox.index', [
                'rows'    => collect(),
                'page'    => 1,
                'hasNext' => false,
                'error'   => $this->friendlyError($e),
            ]);
        }
    }

    /**
     * Show a single message by UID.
     */
    public function show(string $uid)
    {
        try {
            $message = $this->inbox()->query()->leaveUnread()->getMessageByUid($uid);
        } catch (\Throwable $e) {
            Log::warning('IMAP show failed: '.$e->getMessage());

            return view('admin.mailbox.show', ['message' => null, 'error' => $this->friendlyError($e)]);
        }

        if (! $message) {
            abort(404);
        }

        $attachments = $message->getAttachments()->values()->map(fn ($a, $i) => [
            'index' => $i,
            'name'  => (string) ($a->name ?: 'attachment-'.$i),
            'mime'  => $a->getMimeType(),
            'size'  => (int) $a->size,
        ]);

        return view('admin.mailbox.show', [
            'error'       => null,
            'uid'         => $uid,
            'subject'     => $this->str($message->subject) ?: '(no subject)',
            'from'        => $this->fromLabel($message),
            'to'          => $this->addressLabel($message->to),
            'date'        => $this->safeDate($message->date),
            'html'        => $message->getHTMLBody(),
            'text'        => $message->getTextBody(),
            'attachments' => $attachments,
        ]);
    }

    /**
     * Stream an attachment download (e.g. a bank-statement PDF).
     */
    public function attachment(string $uid, int $index): StreamedResponse
    {
        $message = $this->inbox()->query()->leaveUnread()->getMessageByUid($uid);
        abort_unless($message, 404);

        $attachment = $message->getAttachments()->values()->get($index);
        abort_unless($attachment, 404);

        $name = $attachment->name ?: 'attachment';
        $mime = $attachment->getMimeType() ?: 'application/octet-stream';
        $content = $attachment->content;

        return response()->streamDownload(function () use ($content) {
            echo $content;
        }, $name, ['Content-Type' => $mime]);
    }

    /**
     * Connect and open the INBOX folder.
     */
    private function inbox()
    {
        $client = Client::account('default');
        $client->connect();

        return $client->getFolder('INBOX');
    }

    private function fromLabel($message): string
    {
        $first = $message->from?->first();

        if (! $first) {
            return 'Unknown sender';
        }

        $name = trim((string) ($first->personal ?? ''));
        $mail = trim((string) ($first->mail ?? ''));

        return $name !== '' ? $name : ($mail !== '' ? $mail : 'Unknown sender');
    }

    private function addressLabel($attribute): string
    {
        if (! $attribute) {
            return '';
        }

        return collect($attribute->toArray())
            ->map(fn ($a) => trim((string) ($a->mail ?? '')))
            ->filter()
            ->implode(', ');
    }

    private function str($attribute): string
    {
        return $attribute === null ? '' : trim((string) $attribute);
    }

    private function isSeen($message): bool
    {
        try {
            $flags = array_map('strtolower', (array) $message->getFlags()->toArray());

            return in_array('seen', $flags, true);
        } catch (\Throwable) {
            return true; // don't scream "unread" if flags can't be read
        }
    }

    private function safeDate($attribute): ?\Illuminate\Support\Carbon
    {
        try {
            $date = $attribute?->toDate();

            return $date ? \Illuminate\Support\Carbon::instance($date) : null;
        } catch (\Throwable) {
            return null;
        }
    }

    private function friendlyError(\Throwable $e): string
    {
        if (! config('imap.accounts.default.password')) {
            return 'Mailbox not configured yet — add the IMAP_PASSWORD for your PrivateEmail mailbox in the backend .env.';
        }

        return 'Couldn’t reach the mailbox right now. Check the IMAP credentials and try again.';
    }
}
