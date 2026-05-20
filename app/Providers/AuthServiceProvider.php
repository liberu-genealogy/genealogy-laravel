<?php

namespace App\Providers;

use App\Models\Activation;
use App\Models\Addr;
use App\Models\Author;
use App\Models\Category;
use App\Models\Chan;
use App\Models\Citation;
use App\Models\Company;
use App\Models\ConnectedAccount;
use App\Models\Conversation;
use App\Models\Dna;
use App\Models\DnaMatching;
use App\Models\Family;
use App\Models\FamilyEvent;
use App\Models\FamilySlgs;
use App\Models\Gedcom;
use App\Models\Geneanum;
use App\Models\ImportJob;
use App\Models\MediaObject;
use App\Models\MediaObjeectFile;
use App\Models\Message;
use App\Models\Note;
use App\Models\PaypalPlan;
use App\Models\PaypalProduct;
use App\Models\PaypalSubscription;
use App\Models\Person;
use App\Models\PersonAlia;
use App\Models\PersonAnci;
use App\Models\PersonAsso;
use App\Models\PersonEvent;
use App\Models\PersonLds;
use App\Models\PersonName;
use App\Models\PersonNameFone;
use App\Models\PersonNameRomn;
use App\Models\PersonSubm;
use App\Models\Place;
use App\Models\Publication;
use App\Models\Refn;
use App\Models\Repository;
use App\Models\ResearchSpace;
use App\Models\Role;
use App\Models\Source;
use App\Models\SourceData;
use App\Models\SourceDataEven;
use App\Models\SourceRef;
use App\Models\SourceRefEven;
use App\Models\SourceRepo;
use App\Models\Subm;
use App\Models\Subn;
use App\Models\Team;
use App\Models\Tree;
use App\Models\Type;
use App\Models\User;
use App\Models\UserSocial;
use App\Policies\ActivationPolicy;
use App\Policies\AddrPolicy;
use App\Policies\AuthorPolicy;
use App\Policies\CategoryPolicy;
use App\Policies\ChanPolicy;
use App\Policies\CitationPolicy;
use App\Policies\CompanyPolicy;
use App\Policies\ConnectedAccountPolicy;
use App\Policies\ConversationPolicy;
use App\Policies\DnaMatchingPolicy;
use App\Policies\DnaPolicy;
use App\Policies\FamilyEventPolicy;
use App\Policies\FamilyPolicy;
use App\Policies\FamilySlgsPolicy;
use App\Policies\GedcomPolicy;
use App\Policies\GeneanumPolicy;
use App\Policies\ImportJobPolicy;
use App\Policies\MediaObjectPolicy;
use App\Policies\MediaObjeectFilePolicy;
use App\Policies\MessagePolicy;
use App\Policies\NotePolicy;
use App\Policies\PaypalPlanPolicy;
use App\Policies\PaypalProductPolicy;
use App\Policies\PaypalSubscriptionPolicy;
use App\Policies\PersonAliaPolicy;
use App\Policies\PersonAnciPolicy;
use App\Policies\PersonAssoPolicy;
use App\Policies\PersonEventPolicy;
use App\Policies\PersonLdsPolicy;
use App\Policies\PersonNameFonePolicy;
use App\Policies\PersonNamePolicy;
use App\Policies\PersonNameRomnPolicy;
use App\Policies\PersonPolicy;
use App\Policies\PersonSubmPolicy;
use App\Policies\PlacePolicy;
use App\Policies\PublicationPolicy;
use App\Policies\RefnPolicy;
use App\Policies\RepositoryPolicy;
use App\Policies\ResearchSpacePolicy;
use App\Policies\RolePolicy;
use App\Policies\SourceDataEvenPolicy;
use App\Policies\SourceDataPolicy;
use App\Policies\SourcePolicy;
use App\Policies\SourceRefEvenPolicy;
use App\Policies\SourceRefPolicy;
use App\Policies\SourceRepoPolicy;
use App\Policies\SubmPolicy;
use App\Policies\SubnPolicy;
use App\Policies\TeamPolicy;
use App\Policies\TreePolicy;
use App\Policies\TypePolicy;
use App\Policies\UserPolicy;
use App\Policies\UserSocialPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Activation::class       => ActivationPolicy::class,
        Addr::class             => AddrPolicy::class,
        Author::class           => AuthorPolicy::class,
        Category::class         => CategoryPolicy::class,
        Chan::class             => ChanPolicy::class,
        Citation::class         => CitationPolicy::class,
        Company::class          => CompanyPolicy::class,
        ConnectedAccount::class => ConnectedAccountPolicy::class,
        Conversation::class     => ConversationPolicy::class,
        Dna::class              => DnaPolicy::class,
        DnaMatching::class      => DnaMatchingPolicy::class,
        Family::class           => FamilyPolicy::class,
        FamilyEvent::class      => FamilyEventPolicy::class,
        FamilySlgs::class       => FamilySlgsPolicy::class,
        Gedcom::class           => GedcomPolicy::class,
        Geneanum::class         => GeneanumPolicy::class,
        ImportJob::class        => ImportJobPolicy::class,
        MediaObject::class      => MediaObjectPolicy::class,
        MediaObjeectFile::class => MediaObjeectFilePolicy::class,
        Message::class          => MessagePolicy::class,
        Note::class             => NotePolicy::class,
        PaypalPlan::class       => PaypalPlanPolicy::class,
        PaypalProduct::class    => PaypalProductPolicy::class,
        PaypalSubscription::class => PaypalSubscriptionPolicy::class,
        Person::class           => PersonPolicy::class,
        PersonAlia::class       => PersonAliaPolicy::class,
        PersonAnci::class       => PersonAnciPolicy::class,
        PersonAsso::class       => PersonAssoPolicy::class,
        PersonEvent::class      => PersonEventPolicy::class,
        PersonLds::class        => PersonLdsPolicy::class,
        PersonName::class       => PersonNamePolicy::class,
        PersonNameFone::class   => PersonNameFonePolicy::class,
        PersonNameRomn::class   => PersonNameRomnPolicy::class,
        PersonSubm::class       => PersonSubmPolicy::class,
        Place::class            => PlacePolicy::class,
        Publication::class      => PublicationPolicy::class,
        Refn::class             => RefnPolicy::class,
        Repository::class       => RepositoryPolicy::class,
        ResearchSpace::class    => ResearchSpacePolicy::class,
        Role::class             => RolePolicy::class,
        Source::class           => SourcePolicy::class,
        SourceData::class       => SourceDataPolicy::class,
        SourceDataEven::class   => SourceDataEvenPolicy::class,
        SourceRef::class        => SourceRefPolicy::class,
        SourceRefEven::class    => SourceRefEvenPolicy::class,
        SourceRepo::class       => SourceRepoPolicy::class,
        Subm::class             => SubmPolicy::class,
        Subn::class             => SubnPolicy::class,
        Team::class             => TeamPolicy::class,
        Tree::class             => TreePolicy::class,
        Type::class             => TypePolicy::class,
        User::class             => UserPolicy::class,
        UserSocial::class       => UserSocialPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        //
    }
}
